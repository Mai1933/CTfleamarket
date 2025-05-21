<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([CategoriesTableSeeder::class]);
        $this->call([ItemsTableSeeder::class]);
        $this->call([Category_itemTableSeeder::class]);
        $this->call([UsersTableSeeder::class]);
    }
}
