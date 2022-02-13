<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function listUsers(Request $request)
    {
        $results = User::query();
        if ($request->has('q')) {
            $results->orWhere('name', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('steam_name', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('steamid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('guid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhereHas('names', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->input('q').'%');
            });
        }

        if ($request->has('individual-perms')) {
            if (1 == $request->input('individual-perms')) {
                $results->has('permissions', '>', 0);
            } else {
                $results->has('permissions', '=', 0);
            }
        }

        if ($request->has('has-groups')) {
            if (1 == $request->input('has-groups')) {
                $results->has('roles', '>', 0);
            } else {
                $results->has('roles', '=', 0);
            }
        }

        if ($request->has('group')) {
            $results->whereHas('roles', function ($query) use ($request) {
                $query->where('name', $request->input('group'));
            });
        }

        $results = $results->with(['roles', 'permissions'])->paginate(20);
        $roles = Role::has('users')->get();

        return view('acl.users.list')
            ->with('results', $results)
            ->with('q', $request->input('q'))
            ->with('roles', $roles);
    }

    public function newUserPage()
    {
        $roles = Role::all();

        return view('acl.users.new')->with('roles', $roles);
    }

    public function newUser(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required|unique:users',
            'steamid' => 'required|unique:users',
            'email'   => 'required|email|unique:users',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->steamid = $request->steamid;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->roles);
        $user->save();

        return redirect(route('acl-users'))->with('status', 'Utilisateur ajouté avec succès');
    }

    public function editUserPage($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('acl.users.edit')->with('user', $user)->with('permissions', $permissions)->with('roles', $roles);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Si l'utilisateur n'est pas admin, nous vérifions que les données pertinentes arrivent
//        if(!$user->isAdmin()) {
//            $this->validate($request, [
//                'name' => [
//                    'required',
//                    Rule::unique('users')->ignore($user->id),
//                ],
//                'steamid' => [
//                    'required',
//                    Rule::unique('users')->ignore($user->id),
//                ],
//                'email' => [
//                    'required',
//                    'email',
//                    Rule::unique('users')->ignore($user->id),
//                ]
//            ]);
//        }

//         Si ce n'est pas le cas, enregistrez les données modifiées
        if (! $user->isAdmin()) {
//            $user->name = $request->name;
//            $user->steamid = $request->steamid;
//            $user->email = $request->email;
            if (isset($request->disabled)) {
                $user->disabled = true;
                $user->disabled_reason = "@disabled";
                $user->disabled_at = Carbon::now();

                $params=[
                    'key' => '6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714',
                    'id' => 10820, 
                    'guid' => $user->steamid,
                    'mode' => 'remove'
                ];

                $defaults = array(
                    CURLOPT_URL => 'https://armaremoteadmin.com/api/extern/v1/IWhitelist/ChangeWhitelist.ashx',
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $params,
                );

                $ch = curl_init();
                curl_setopt_array($ch, $defaults);
                $whitelist = curl_exec($ch);

                curl_close($ch);

            } elseif (is_null($request->disabled)) {
                $user->disabled = false;
                $user->disabled_reason = NULL;
                $user->disabled_at = NULL;

                if ($user->imported_exam_exempt || $user->exams()->where('passed', true)->where('interview_passed', true)->count() > 0) {
                    $params=[
                        'key' => '6074227175160E21F086C17953297233234F3F0C833134A222628D24B5E1A714',
                        'id' => 10820, 
                        'guid' => $user->steamid,
                        'mode' => 'change',
                    ];

                    $defaults = array(
                        CURLOPT_URL => 'https://armaremoteadmin.com/api/extern/v1/IWhitelist/ChangeWhitelist.ashx',
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query($params)."&settings=[true,false,false,'".$user->username($user)."']",
                    );

                    $ch = curl_init();
                    curl_setopt_array($ch, $defaults);
                    $whitelist = curl_exec($ch);

                    curl_close($ch);
                }
            }
        }

        // Les autorisations et les groupes sont toujours sauvegardés
        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);
        $user->save();

        return redirect(route('acl-users-edit', $user))->with('status', 'Utilisateur édité avec succès');
    }
}
