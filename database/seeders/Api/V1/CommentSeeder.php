<?php

namespace Database\Seeders\Api\V1;

use App\Models\Blog;
use App\Models\Comment;
use Database\Factories\CommentFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::factory(10)
        ->hasComments(3)
        ->create();
    }
}
