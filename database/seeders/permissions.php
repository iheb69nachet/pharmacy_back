<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $editUsers = Permission::create(['name' => 'edit users']);
        $viewUsers = Permission::create(['name' => 'view users']);
        $deleteUsers = Permission::create(['name' => 'delete users']);
        $publishUsers = Permission::create(['name' => 'publish users']);
        $unpublishUsers = Permission::create(['name' => 'unpublish users']);
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo([$viewUsers,$editUsers, $deleteUsers, $publishUsers, $unpublishUsers]);

    }
}
