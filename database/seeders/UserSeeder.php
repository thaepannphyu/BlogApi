<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Mg Mg',
            'email' => 'mgmg@example.com',
            'password' => '12345678'
        ]);

        User::factory()->create([
            'name' => 'Aung Aung',
            'email' => 'aung@example.com',
            'password' => '129888670'
            
        ]);

        $users= User::factory(10)
        ->hasSubscribers(3)
        ->create(['password'=>'12345678']);

        $blogs = Blog::factory()->count(10)->create();
        $blogs->each(function ($blog) use ($users) {
            $users->random(3)->each(function ($user) use ($blog) {
                $blog->comments()->attach($user, ['body' => 'Sample comment body']);
            });
        });
    }
}
