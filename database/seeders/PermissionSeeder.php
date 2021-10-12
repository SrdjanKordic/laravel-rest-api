<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsArray = array(
            [
                'name' => 'user-access',
                'description' => 'Access to users page'
            ],
            [
                'name' => 'user-create',
                'description' => 'Create new users'
            ],
            [
                'name' => 'user-update',
                'description' => 'Update users data'
            ],
            [
                'name' => 'user-delete',
                'description' => 'Remove users'
            ],
            [
                'name' => 'settings-general-access',
                'description' => 'Access to general settings'
            ],
            [
                'name' => 'settings-general-update',
                'description' => 'Update general settings'
            ],
            [
                'name' => 'role-access',
                'description' => 'Access to roles in settings page'
            ],
            [
                'name' => 'role-create',
                'description' => 'Create new role'
            ],
            [
                'name' => 'role-update',
                'description' => 'Update existing role'
            ],
            [
                'name' => 'role-delete',
                'description' => 'Remove role'
            ],
        );

        DB::table('permissions')->truncate();

        foreach($permissionsArray as $permission){
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'description' => $permission['description'],
            ]);
        }
        
    }
}
