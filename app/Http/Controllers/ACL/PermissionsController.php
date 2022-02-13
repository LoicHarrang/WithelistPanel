<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use App\Permission;

class PermissionsController extends Controller
{
    /**
     * Création d'une nouvelle instance controller
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function listPermissions()
    {
        $permissions = Permission::all();

        return view('acl.permissions.list')->with('permissions', $permissions);
    }
}
