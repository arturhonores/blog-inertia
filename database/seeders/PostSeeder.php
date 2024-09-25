<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Tag;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los tags creados por el TagSeeder
        $tags = Tag::all();

        // Crear 10 posts utilizando la factory
        Post::factory(10)->create();
    }
}
