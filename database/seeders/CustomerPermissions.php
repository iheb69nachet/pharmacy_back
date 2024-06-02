<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CustomerPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $editCustomer = Permission::create(['name' => 'edit customers']);
        $viewCustomer = Permission::create(['name' => 'view customers']);
        $deleteCustomer = Permission::create(['name' => 'delete customers']);
        $publishCustomer = Permission::create(['name' => 'publish customers']);
        $unpublishCustomer = Permission::create(['name' => 'unpublish customers']);
        $adminRole = Role::findByName('admin');
        $responsableRole = Role::findByName('responsable');
        $adminRole->givePermissionTo([$viewCustomer,$editCustomer, $deleteCustomer, $publishCustomer, $unpublishCustomer]);
        $responsableRole->givePermissionTo([$viewCustomer,$editCustomer, $deleteCustomer, $publishCustomer, $unpublishCustomer]);

    }
}
