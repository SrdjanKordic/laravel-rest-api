<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('providers')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('users')->insert([
            'name' => 'Srdjan Kordic',
            'email' => 'srdjank90@gmail.com',
            'role_id' => 1,
            'password' => bcrypt('Lika1990!'),
            'country' => 'Yugoslavia',
            'phone' => '+381655264567'
        ]);
    }
}
