<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Api\V1\BlogSeeder;
use Database\Seeders\Api\V1\CategorySeeder;
use Database\Seeders\Api\V1\CommentSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            BlogSeeder::class,
            CategorySeeder::class,
            CommentSeeder::class
        ]);
       
     
    }
}
