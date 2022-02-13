<?php
/**
 * Copyright (c) 2020. Arma 3 Frontière
 * Tout droit réservés
 * Par Loic Shmit et Sharywan
 */

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
