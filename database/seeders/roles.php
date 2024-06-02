<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'responsable']);
        $role3 = Role::create(['name' => 'employe']);



    }
}
