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
                'name' => 'USER_ACCESS',
                'description' => 'Access to users page'
            ],
            [
                'name' => 'USER_CREATE',
                'description' => 'Create new users'
            ],
            [
                'name' => 'USER_UPDATE',
                'description' => 'Update users data'
            ],
            [
                'name' => 'USER_DELETE',
                'description' => 'Remove users'
            ],
            [
                'name' => 'SETTINGS_GENERAL_ACCESS',
                'description' => 'Access to general settings'
            ],
            [
                'name' => 'SETTINGS_GENERAL_UPDATE',
                'description' => 'Update general settings'
            ],
            [
                'name' => 'ROLE_ACCESS',
                'description' => 'Access to roles in settings page'
            ],
            [
                'name' => 'ROLE_CREATE',
                'description' => 'Create new role'
            ],
            [
                'name' => 'ROLE_UPDATE',
                'description' => 'Update existing role'
            ],
            [
                'name' => 'ROLE_DELETE',
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
