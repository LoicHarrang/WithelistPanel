<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $role = new \App\Role();
        $role->name = 'debug';
        $role->display_name = 'Debug';
        $role->description = 'Groupe résercé aux developpeurs';
        $role->save();

        $role->syncPermissions(\App\Permission::all());
        $role->save();
    }
}
