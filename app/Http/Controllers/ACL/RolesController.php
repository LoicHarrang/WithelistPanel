<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function listRoles()
    {
        $roles = Role::all();

        return view('acl.roles.list')->with('roles', $roles);
    }

    public function newRolePage()
    {
        $permissions = Permission::all();

        return view('acl.roles.new')->with('permissions', $permissions);
    }

    public function newRole(Request $request)
    {
        $this->validate($request, [
            'name'         => 'required|unique:roles',
            'display_name' => 'required|unique:roles',
            'description'  => 'required|min:5|max:140',
        ], [
            'description.min' => 'La description doit avoir au moins :min caractères.',
            'description.max' => 'La description doit avoir au maximum :min caractères.',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        $role->syncPermissions($request->permissions);
        $role->save();

        return redirect(route('acl-roles'))->with('status', 'Groupe crée avec succès');
    }

    public function editRolePage($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('acl.roles.edit')->with('role', $role)->with('permissions', $permissions);
    }

    public function editRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($role->id),
            ],
            'display_name' => [
                'required',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => 'required|min:5|max:140',
        ], [
            'description.min' => 'La description doit avoir au moins :min caractères.',
            'description.max' => 'La description doit avoir au maximum :min caractères.',
        ]);

        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        $role->syncPermissions($request->permissions);
        $role->save();

        return redirect(route('dash-roles-edit', $role))->with('status', 'Groupe édité avec succès');
    }

    public function deleteRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return redirect(route('acl-roles', $role))->with('status', 'Groupe supprimé avec succès');
    }
}
