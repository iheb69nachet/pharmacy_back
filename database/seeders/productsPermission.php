<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class productsPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $editProducts = Permission::create(['name' => 'edit products']);
        $viewProducts = Permission::create(['name' => 'view products']);
        $deleteProducts = Permission::create(['name' => 'delete products']);
        $publishProducts = Permission::create(['name' => 'publish products']);
        $unpublishProducts = Permission::create(['name' => 'unpublish products']);
        $adminRole = Role::findByName('admin');
        $responsableRole = Role::findByName('responsable');
        $employeRole = Role::findByName('employe');
        $adminRole->givePermissionTo([$viewProducts,$editProducts, $deleteProducts, $publishProducts, $unpublishProducts]);
        $responsableRole->givePermissionTo([$viewProducts,$editProducts, $deleteProducts, $publishProducts, $unpublishProducts]);
        $employeRole->givePermissionTo([$viewProducts]);

    }
}
