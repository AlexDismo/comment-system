<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->text(500),
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->unique()->safeEmail,
            'author_url' => $this->faker->url,
        ];
    }
    public function configure(): Factory
    {
        return $this->afterCreating(function (Comment $comment) {
            if (Comment::exists() && rand(1, 100) <= 90) {
                $comment->parent_id = Comment::all()->random()->id;
                $comment->save();
            }
        });
    }
}
