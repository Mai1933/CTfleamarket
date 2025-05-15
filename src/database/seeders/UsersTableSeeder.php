<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'id' => 0,
            'name' => '1から5出品者',
            'password' => 'password',
            'email' => '1to5@email.com',
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
        $param = [
            'id' => 1,
            'name' => '6から10出品者',
            'password' => 'password',
            'email' => '6to10@email.com',
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
        $param = [
            'id' => 2,
            'name' => '紐づけなし',
            'password' => 'password',
            'email' => 'noitem@email.com',
            'email_verified_at' => now(),
        ];
        DB::table('users')->insert($param);
    }
}
