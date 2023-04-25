<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 5),
            'film_id' => $this->faker->numberBetween(1, 5),
            'parent_id' => null,
            'text' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
            'is_external' => false,
        ];
    }
}
