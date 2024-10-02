<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'Joel Delgado', 'biography' => 'Biografía de Joel...'],
            ['name' => 'Gemma López', 'biography' => 'Biografía de Gemma...'],
            ['name' => 'Manuel Quintero', 'biography' => 'Biografía de Manuel...'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
