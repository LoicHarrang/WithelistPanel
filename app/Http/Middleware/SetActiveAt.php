<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SetActiveAt
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
        return $next($request);
    }

    // Pour que ce soit plus tard au cas où vous seriez déconnecté
    public function terminate($request, $response)
    {
        // Si l'utilisateur est en ligne et que nous ne savions pas qu'il était actif, nous le mettons
        if (Auth::check()) {
            $user = Auth::user();
            // Nous sauvegardons dans le cache pendant 5 minutes s'il est actif, pour éviter de charger la DB
            if (! Cache::has('user_is_active_'.$user->id)) {
                Cache::put('user_is_active_'.$user->id, true, 5);
                Auth::user()->active_at = Carbon::now();
                $user->timestamps = false;
                $user->save();
                $user->timestamps = true;
            }
        }
    }
}
