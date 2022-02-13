<?php

namespace App\Http\Controllers\A3F\Vehicule;

use App\Name;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class VehicleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth','setup_required']);
    }

    public function listOwnVehicles()
    {
        return view('a3f.vehicules.vehicule')->with('player', Auth::user()->player);
    }
}
