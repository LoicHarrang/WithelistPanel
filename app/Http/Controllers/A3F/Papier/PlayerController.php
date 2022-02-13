<?php

namespace App\Http\Controllers\A3F\Papier;

use App\Arma\Player;
use App\User;
use App\Name;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','setup_required']);
    }

    public function search()
    {
        $user = User::with(['roles', 'permissions', 'exams', 'names'])->findOrFail(Auth::user()->id);

        return view('a3f.papier.papier')->with('user', $user);
    }

    public function cadastre()
    {
        return view('a3f.papier.cadastre');
    }
}
