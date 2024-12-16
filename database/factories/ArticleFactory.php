<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'source' => $this->faker->randomElement(['Guardian', 'NYT', 'NewsAPI']),
            'author' => $this->faker->name,
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->sentence),
            'description' => $this->faker->text(200),
            'content' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'url_to_image' => $this->faker->imageUrl(),
            'published_at' => $this->faker->date(),
            'category' => $this->faker->word,
        ];
    }
}
