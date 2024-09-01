<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'meta_title' => $this->faker->text(100),
            'meta_description' => $this->faker->text(200),
            'image_post_url' => $this->faker->imageUrl(930, 620, 'posts'),
            'image_card_url' => $this->faker->imageUrl(290, 210, 'cards'),
            'post_html' => $this->faker->paragraphs(5, true),
            'summary' => $this->faker->text(250),
            'publish_date' => $this->faker->date(),
            'author_id' => Author::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
