<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'id' => 1,
            'name' => '1から5出品者',
            'password' => bcrypt('password'),
            'email' => '1to5@email.com',
            'email_verified_at' => now(),
            'user_image' => 'blue.png'
        ];
        DB::table('users')->insert($param);
        $param = [
            'id' => 2,
            'name' => '6から10出品者',
            'password' => bcrypt('password'),
            'email' => '6to10@email.com',
            'email_verified_at' => now(),
            'user_image' => 'red.png'
        ];
        DB::table('users')->insert($param);
        $param = [
            'id' => 3,
            'name' => '紐づけなし',
            'password' => bcrypt('password'),
            'email' => 'noitem@email.com',
            'email_verified_at' => now(),
            'user_image' => 'no_image.png'
        ];
        DB::table('users')->insert($param);
    }
}
