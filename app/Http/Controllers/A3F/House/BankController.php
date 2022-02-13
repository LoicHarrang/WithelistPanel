<?php

namespace App\Http\Controllers\A3F\House;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth','setup_required']);
    }

    public function viewAccounts()
    {
        return view('a3f.house.propriete');
    }
}
