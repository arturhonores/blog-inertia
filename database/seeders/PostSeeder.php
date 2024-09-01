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

        // Crear 10 posts utilizando la factory y asignarles de 3 a 5 tags aleatorios
        Post::factory(10)->create()->each(function ($post) use ($tags) {
            // Asignar de 3 a 5 tags aleatorios a cada post
            $post->tags()->attach(
                $tags->random(rand(3, 5))->pluck('id')->toArray()
            );
        });
    }
}
