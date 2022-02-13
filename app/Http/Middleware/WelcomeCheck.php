<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WelcomeCheck
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

            // 0. Vérification jeu
            if (is_null($request->user()->is_beta) || is_null($request->user()->discord_id)) {
                return redirect(route('setup-welcome'));
            }
        }

        return $next($request);
    }
}
