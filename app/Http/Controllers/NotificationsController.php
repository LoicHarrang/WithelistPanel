<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();

        // Marquer les notifs comme lues
        $user->unreadNotifications->markAsRead();

        // Retour a la page precedente
        return redirect()->back()->with('status', 'Notification marqué comme lue');
    }
}
