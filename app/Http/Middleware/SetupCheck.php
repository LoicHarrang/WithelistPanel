<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SetupCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $finished = $request->user()->hasFinishedSetup();
            $step = $request->user()->getSetupStep();

            if (is_null($request->user()->discord_id) && ('setup/welcome' != $request->route()->uri) && ('setup/discord_receive' != $request->route()->uri)) {
                return redirect(route('setup-welcome'));
            }

            // -1. Vérifiez si le setup a déjà été effectuée
            if ($finished && $request->is('setup/*') && !is_null($request->user()->discord_id)) {
                return redirect(route('home'));
            }

            // 0. Vérification jeu
            if (0 == $step && ('setup/welcome' != $request->route()->uri) && ('setup/discord_receive' != $request->route()->uri) && ('setup/checkgame' != $request->route()->uri)) {
                return redirect(route('setup-welcome'));
            }

            // 1. Informations
            if (1 == $step && 'setup/info' != $request->route()->uri) {
                return redirect(route('setup-info'));
            }

            // 2. Email
            if ((2 == $step) && ('setup/email' != $request->route()->uri) && ('setup/email/reset' != $request->route()->uri)) {
                return redirect(route('setup-email'));
            }

            // 3. Identité
            if (3 == $step && ! ('setup/intro' == $request->route()->uri || 'setup/name' == $request->route()->uri || 'setup/name/check' == $request->route()->uri)) {
                return redirect(route('setup-name'));
            }

            // 4. Règlement
            if (4 == $step && ! ('setup/rules' == $request->route()->uri)) {
                if (!is_null($request->user()->rules_seen_at) && $request->user()->rules_seen_at->addMinutes(5) < Carbon::now()) {
                    Cache::forget('user.'.\auth()->user()->id.'.getSetupStep');

                    return $next($request);
                }

                return redirect(route('setup-rules'));
            }

            // 5. Examen
            if (5 == $step && ! ($request->is('setup/exam/*') || $request->is('setup/exam') || $request->is('setup/rules'))) {
                return redirect(route('setup-exam'));
            }

            // 5. Examen (Règlement)
            if (5 == $step && $request->is('setup/rules')) {
                if ($request->is('setup/rules') && ! is_null($request->user()->getOngoingExam())) {
                    return redirect(route('setup-exam'))->with('status', 'Tu ne peux pas voir les règles pour le moment.');
                }
            }

            // 6. Forum
            if (6 == $step && ! ($request->is('setup/forum/*') || $request->is('setup/forum'))) {
                return redirect(route('setup-forum'));
            }

            // 7. Entretien
            if (7 == $step && ! ('setup/interview' == $request->route()->uri || $request->is('setup/rules'))) {
                return redirect(route('setup-interview'));
            }

            // 5. Entretien (règlemennt)
            if (7 == $step && $request->is('setup/rules')) {
                if ($request->is('setup/rules') && $request->user()->hasInterviewOngoing()) {
                    return redirect(route('setup-interview'))->with('status', 'Tu ne peux pas voir les règles pour le moment.');
                }
            }
        }

        return $next($request);
    }
}
