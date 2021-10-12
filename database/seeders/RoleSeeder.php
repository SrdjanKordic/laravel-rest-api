<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all()->pluck('id');
        $rolesArray = array(
            [
                'name' => 'Admin',
                'description' => 'Administrator of application',
            ],
            [
                'name' => 'User',
                'description' => 'Registered user',
            ],
        );
        DB::table('roles')->truncate();
        foreach($rolesArray as $role){
            $role = Role::create([
                'name' => $role['name'],
                'description' => $role['description'],
            ]);
            if($role['name'] === 'Admin'){
                $role->permissions()->sync($permissions);
            }else{  
                $role->permissions()->sync([1]);
            }
            
        }
    }
}
