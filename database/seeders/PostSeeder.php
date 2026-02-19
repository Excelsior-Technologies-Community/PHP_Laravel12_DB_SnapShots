<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run()
    {
        Post::create([
            'title' => 'Getting Started with Laravel',
            'content' => 'Laravel is a powerful PHP framework...',
            'is_published' => true
        ]);

        Post::create([
            'title' => 'Database Snapshots in Laravel',
            'content' => 'Database snapshots are great for backups...',
            'is_published' => true
        ]);

        Post::create([
            'title' => 'Draft Post Example',
            'content' => 'This post is still in draft...',
            'is_published' => false
        ]);

        // Create additional posts using factory
        Post::factory()->count(10)->create();
    }
}