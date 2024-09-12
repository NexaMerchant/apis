<?php

namespace NexaMerchant\Apis\Database\Seeds;


use NexaMerchant\Apis\Models\Permission;
use NexaMerchant\Apis\Models\Role;
use NexaMerchant\Apis\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Lauthz\Facades\Enforcer;

class Test extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Enforcer::addPermissionForUser('admin', '', '/dashboard');
        // Enforcer::addPermissionForUser('admin', '', '/dashboard/analysis');
        // Enforcer::addPermissionForUser('admin', '', '/dashboard/workbench');

        // $data = Enforcer::getRolesForUser('1');
        // $data = Enforcer::getPermissionsForUser('roota');




        $bool = Enforcer::enforce((string)1, '', '/system/role');
        // dump($bool);
        dump($bool);


    }
}
