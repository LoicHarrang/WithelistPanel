<?php

namespace App\Http\Controllers;

use App\Name;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','welcome_passed'])->except('verifyCode');
    }

    public function accountPage()
    {
        $user = User::with(['roles', 'permissions', 'exams', 'names'])->findOrFail(Auth::user()->id);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://armaremoteadmin.com/api/extern/v1/IWhitelist/GetWhitelist.ashx?key=6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714&id=10820");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $whitelist = curl_exec($ch);
        curl_close($ch);

        return view('compte')->with('user', $user)->with('player', Auth::user()->player)->with('whitelist', $whitelist);
    }

    public function verifyCode($code)
    {
        $user = User::where('email_verified_token', $code)->firstOrFail();
        if (! $user->email_verified) {
            $user->email_verified = true;
            $user->email_verified_token = null;
            $user->email_verified_token_at = null;
            $user->email_verified_at = Carbon::now();
            $user->timestamps = false;
            $user->save();
            $user->timestamps = true;
            Cache::forget('user.'.$user->id.'.getSetupStep');
            if (Auth::check()) {
                return redirect(route('setup-name'))->with('status', __('setup.email.verified'));
            }

            return view('verified');
        }
    }

    public function namePage(Request $request)
    {
        if (0 == $request->user()->name_changes_remaining) {
            abort(403);
        }
        $user = Auth::user();

        return view('user.namechange')
            ->with('user', $user);
    }

    public function nameCheck(Request $request)
    {
        if (0 == $request->user()->name_changes_remaining) {
            abort(403);
        }
        $this->validate($request, [
            'firstName' => 'required|min:3|max:14',
            'lastName'  => 'required|min:3|max:14',
        ]);
        $name = trim($request->input('firstName')).' '.trim($request->input('lastName'));
        if (User::where('name', 'LIKE', $name)->count() > 0
            || Name::where('name', 'LIKE', $name)->count() > 0) {
            return 'taken';
        }

        return 'OK';
    }

    /**
     * POST changement d'identité
     *
     * @param Request $request
     *
     * @return string
     */
    public function name(Request $request)
    {
        if (0 == $request->user()->name_changes_remaining) {
            abort(403);
        }
        // Vérifier que le nom et le prénom ont été envoyés
        $this->validate($request, [
            'firstName' => 'required|min:3|max:14',
            'lastName'  => 'required|min:3|max:14',
        ]);

        // Efface les espaces inutiles dans le nom complet et ajoute un espace entre le prénom et le nom de famille
        $fullName = trim($request->input('firstName')).' '.trim($request->input('lastName'));

        // Vérifiez avec la correspondance "loose" LIKE pour voir s'il y a des noms similaires dans la bdd ou
        // en entretien
        if (User::where('name', 'LIKE', $fullName)->count() > 0
            || Name::where('name', 'LIKE', $fullName)->count() > 0) {
            // On retourne qu'elle est déjà prise afin que la page informe l'utilisateur
            return 'taken';
        }

        $user = Auth::user();

        $name = new Name();

        $correctedName = rtrim($this->correctSpelling($this->titleCase(str_replace('´', '', $fullName))));
        if ($correctedName != $fullName) {
            $name->original_name = $fullName;
        }

        $name->name = $correctedName;
        $name->type = 'change';
        $name->needs_review = true;
        $user->names()->save($name);

        // Supprimer le dernier changement de nom afin qu'il ne puisse plus être modifié
        $user->name_changes_remaining = 0; // Pour l'instant, nous le mettons à zéro, plus tard nous pourrions soustraire
        $user->name_changes_reason = null;
        $user->save();
        Cache::forget('user.'.$user->id.'.getSetupStep');

//        $user->name = $name;
//        $user->timestamps = false; // Como es un cambio que no lo ha iniciado nadie ni importa realmente actualizar
//                                   // la fecha, no guardamos los timestamps.
//        $user->save();
//        $user->timestamps = true;

        // Nous informons la page qu'il n'y a pas de problème et que vous pouvez poursuivre le processus.
        return 'OK';
    }

    public function resetEmail(Request $request)
    {
        $user = $request->user();

        if (! $user->canEnableEmail()) {
            abort(403, 'Changement d\email impossible');
        }

        $user->email = null;
        $user->email_verified = false;
        $user->email_verified_token = null;
        $user->email_verified_at = null;
        if ($request->has('disable')) {
            // L'utilisateur veut désactiver.
            $user->email_enabled = false;
            $user->email_disabled_at = Carbon::now();
            $user->save();
            Cache::forget('user.'.$user->id.'.getSetupStep');

            return redirect(route('compte'));
        } else {
            // L'utilisateur veut activer. Vous serez redirigé vers cette page.
            $user->email_enabled = null;
            $user->save();
            Cache::forget('user.'.$user->id.'.getSetupStep');

            return redirect(route('setup-email'));
        }
    }

    public function correctSpelling($name)
    {
        return strtr(strtr($name, config('dash.nombres')), config('dash.except'));
    }

    /**
     * http://php.net/manual/es/function.ucwords.php#112795.
     *
     * @param $string
     * @param array $delimiters
     * @param array $exceptions
     *
     * @return mixed|string
     */
    public function titleCase($string, $delimiters = [' ', '-', '.', "'", "O'", 'Mc'], $exceptions = ['de', 'da', 'dos', 'das', 'do', 'del', 'I', 'II', 'III', 'IV', 'V', 'VI'])
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = [];
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, 'UTF-8'), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, 'UTF-8');
                } elseif (in_array(mb_strtolower($word, 'UTF-8'), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, 'UTF-8');
                } elseif (! in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        }//foreach
        return $string;
    }
}
