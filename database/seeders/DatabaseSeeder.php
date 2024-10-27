<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Author;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            AuthorSeeder::class,
            PostSeeder::class
        ]);
    }
    //seeder condicionales
    // {
    //     if (User::count() == 0) {
    //         $this->call(UserSeeder::class);
    //     }

    //     if (Category::count() == 0) {
    //         $this->call(CategorySeeder::class);
    //     }

    //     if (Tag::count() == 0) {
    //         $this->call(TagSeeder::class);
    //     }

    //     if (Author::count() == 0) {
    //         $this->call(AuthorSeeder::class);
    //     }

    //     if (Post::count() == 0) {
    //         $this->call(PostSeeder::class);
    //     }
    // }
}
