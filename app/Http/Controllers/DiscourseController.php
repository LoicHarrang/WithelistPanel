<?php

namespace App\Http\Controllers;

use Cviebrock\DiscoursePHP\SSOHelper;
use Illuminate\Http\Request;
use Syntax\SteamApi\Facades\SteamApi;

class DiscourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function sso(Request $request)
    {
        $secret = config('dash.discourse_secret');
        if (is_null($secret)) {
            abort(404);
        }

        $user = \Auth::user();

        // Si l'utilisateur n'a pas entré d'email, redirection
        if($user->getSetupStep() < 3) {
            return redirect(route('setup-info'));
        }

        $sso = new SSOHelper();
        $sso->setSecret($secret);

        // Chargement du payload passé par Discourse
        $payload = $_GET['sso'];
        $signature = $_GET['sig'];

        // validation du payload
        if (! ($sso->validatePayload($payload, $signature))) {
            // invalide, rejet
            abort(403);
        }

        $nonce = $sso->getNonce($payload);

        // Necessaire et doit être unique à l'application
        $userId = $user->id;

        // Required and must be consistent with your application
        $userEmail = $user->email;

        if (is_null($userEmail) || ! $user->email_verified) {
            return view('errors.emailerror');
        }

        // Groups to check membership
        // available types:
        // - player_attribute: a player's attribute (numeric values only) must be at least min_level or higher
        //     - column_name   the name of the column of the table to check
        //     - min_level   the minimum lever for which group membership is granted
        // - id_in_general: a player's SteamID must be present in the specified general table config field
        //     - key    the value of the key column of the row in which search for the SteamID
        // - license: check if the player has a specific license id
        //     - license_id    the id of the license that the player must have
        // TODO move to config

        $groups = [
            [
                'group_name' => 'ems',
                'type' => 'player_attribute',
                'column_name' => 'ems',
                'min_level' => '1',
            ],
            [
                'group_name' => 'jefatura-ems',
                'type' => 'player_attribute',
                'column_name' => 'ems',
                'min_level' => '9',
            ],
            [
                'group_name' => 'policia',
                'type' => 'player_attribute',
                'column_name' => 'cop',
                'min_level' => '1',
            ],
            [
                'group_name' => 'jefatura-policia',
                'type' => 'player_attribute',
                'column_name' => 'cop',
                'min_level' => '7',
            ],
            [
                'group_name' => 'juez',
                'type' => 'player_attribute',
                'column_name' => 'legal',
                'min_level' => '6',
            ],
            [
                'group_name' => 'fiscal',
                'type' => 'player_attribute',
                'column_name' => 'da',
                'min_level' => '6',
            ],
            [
                'group_name' => 'moteros',
                'type' => 'player_attribute',
                'column_name' => 'biker',
                'min_level' => '2',
            ],
            [
                'group_name' => 'casino',
                'type' => 'player_attribute',
                'column_name' => 'mobster',
                'min_level' => '2',
            ],
            [
                'group_name' => 'zumos',
                'type' => 'player_attribute',
                'column_name' => 'mafia',
                'min_level' => '2',
            ],
            [
                'group_name' => 'gobernador',
                'type' => 'id_in_general',
                'key' => 'currentMayorGUID',
            ],
            [
                'group_name' => 'abogacia',
                'type' => 'license',
                'license_id' => 20,
            ],

        ];

        $result = [
            'add' => [],
            'remove' => [],
        ];

        // Identité
        $name = null;
        $hideName = false; // will not send a name to Discourse (in cases where we use Steam display name)

        if(!is_null($user->player) && $user->hasFinishedSetup()) {
            // Si l'utilisateur a joué sur le serveur (son personnage existe) et qu'il a fini le setup
            if (! is_null($user->name)) { // Vérification si l'utilisateur a un nom spécial
                $name = $user->name;
            } else {
                $name = $user->getActiveName();
            }

            $result['add'][] = 'whitelist'; // A cette étape, nous savons si l'utilisateur est withelist. Ajout au groupe.
            $player = $user->player;
            // Go through each available group and see if the player meets the criteria required for membership.
            foreach ($groups as $group) {
                if($group['type'] == 'player_attribute') {
                    // player_attribute type
                    if($player->{$group['column_name']} >= $group['min_level']) {
                        $result['add'][] = $group['group_name'];
                    } else {
                        $result['remove'][] = $group['group_name'];
                    }
                } elseif ($group['type'] == 'id_in_general') {
                    // id_in_general type
                    $currentValue = \Cache::remember('forum-group-check.'.$group['group_name'].'.value', 5, function() use($group) {
                        return \DB::connection('a3f')->table('general')->where('key', $group['key'])->first()->value;
                        // cache result for 5 minutes
                    });
                    if(is_null($currentValue)) {
                        $result['remove'][] = $group['group_name'];
                    } else {
                        if (strpos($currentValue, $user->steamid) !== false) {
                            $result['add'][] = $group['group_name'];
                        } else {
                            $result['remove'][] = $group['group_name'];
                        }
                    }
                } elseif ($group['type'] == 'license') {
                    // license type
                    $exists = \Cache::remember('forum-group-check.'.$group['group_name'].'.exists', 5, function() use($user, $group) {
                        return !is_null(\DB::connection('a3f')->table('licenses')->where(['steamid' => $user->steamid, 'license' => $group['license_id']])->first());
                        // cache result for 5 minutes
                    });
                    if($exists) {
                        $result['add'][] = $group['group_name'];
                    } else {
                        $result['remove'][] = $group['group_name'];
                    }
                }
            }
        } else {
            // Enlever l'utilisateur de tout groupe (non whithelist, peut importe le groupe)
            foreach ($groups as $group) {
                $result['remove'][] = $group['group_name'];
            }
            $result['remove'][] = 'whitelist';
            // Si l'utilisateur a fini le setup, montrer le nom RP. Sinon, montrer le nom Steam.
            if($user->hasFinishedSetup() || !is_null($user->player())) {
                if (! is_null($user->name)) {
                    $name = $user->name;
                } else {
                    if(is_null($user->getActiveName())) {
                        // Si l'utilisateur n'a pas un bon nom RP, montrer le nom Steam.
                        $name = SteamApi::user($user->steamid)->getPlayerSummaries()[0]->personaName;
                        $hideName = true;
                    } else {
                        $name = $user->getActiveName();
                    }
                }
            } else {
                $name = SteamApi::user($user->steamid)->getPlayerSummaries()[0]->personaName;
                $hideName = true;
            }
        }

        if($hideName) {
            $finalName = null;
        } else {
            $finalName = $name;
        }

        $extraParameters = [
            'username'           => strtolower(str_replace(' ', '', $name)),
            'name'               => $finalName,
            'require_activation' => false,
            'add_groups' => implode(',', $result['add']),
            'remove_groups' => implode(',', $result['remove']),
        ];

        // build query string and redirect back to the Discourse site
        $query = $sso->getSignInString($nonce, $userId, $userEmail, $extraParameters);
        header('Location: '.config('dash.discourse_sso_url').'?'.$query);
        exit(0);
    }
}
