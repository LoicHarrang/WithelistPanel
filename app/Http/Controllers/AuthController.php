<?php

namespace App\Http\Controllers;

use App\Page;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Invisnik\LaravelSteamAuth\SteamAuth;
use RestCord\DiscordClient;

class AuthController extends Controller
{
    /**
     * @var SteamAuth
     */
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->middleware('guest');
        $this->steam = $steam;
    }

    public function loginPage(Request $request)
    {
        if (! is_null($request->session()->get('status'))) {
            return view('disabled')->with('reason', $request->session()->get('status'));
        }

        $landing = \Cache::remember('login-landing', 5, function() {
            return Page::where('slug', 'landing')->first();
        });

        return view('login')->with('landing', $landing);
    }

    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (! is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();
                if (is_null($user)) {
                    if (!config('dash.registrations_enabled')) {
                        return redirect('/')->with('status', 'L\'insertion de nouveau dossier d\'inscription est actuellement désactivé !');
                    }
                    $user = new User();
                    $user->steamid = $info->steamID64;
                    $guid = $user->guid; // pour generer l'UID
                    $user->created_at = Carbon::now();
                    $user->save();
                    Auth::login($user, true);

                    return redirect(route('setup-welcome')); // redirection du nouvel utilisateur
                }
                if ($user->isDisabled()) {
                    if ('@pegi' == $user->disabled_reason) {
                        return redirect(route('pegi'));
                    }
                    if (key_exists($user->disabled_reason, config('dash.disabled_reasons'))) {
                        return redirect('/')->with('status', config('dash.disabled_reasons')[$user->disabled_reason]);
                    }

                    return redirect('/')->with('status', $user->disabled_reason);
                }
                // Connection
                Auth::login($user, true);
                if (is_null($user->active_at)) {
                    return redirect(route('setup-welcome'));
                }

                if (!is_null($user->discord_id)) {
                    $discord = new DiscordClient(['token' => config('dash.token_discord')]); // Token is required
                    try {
                        $user_discord = $discord->guild->getGuildMember(['guild.id' => 605812975848128540,'user.id' => intval($user->discord_id)]);
                        foreach ($user_discord->roles as $key => $value) {
                            if (($value == 654412768148979732) || ($value == 642731221817884683) || ($value == 607940362094247946) || ($value == 607941367406002196) && is_null($user->is_beta)) {
                                $user->is_beta = 1;
                                $user->save();
                            }
                        }
                    } catch (\GuzzleHttp\Command\Exception\CommandClientException $e) {}
                } else {
                    return redirect(route('setup-discord'));
                }

                return redirect()->intended('home'); // redirection à l'etape voulu
            }
        }

        return $this->steam->redirect(); // redirection sur la page login de steam
    }

    public function pegi()
    {
        return view('pegi');
    }

    public function altis()
    {
        // si non activé
        if (! config('dash.altis_enabled')) {
            abort(404);
        }

        return view('altis');
    }
}
