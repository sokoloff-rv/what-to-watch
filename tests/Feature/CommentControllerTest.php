<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();
        Comment::factory()->count(10)->create([
            'film_id' => $film->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/films/{$film->id}/comments");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'film_id',
                    'parent_id',
                    'text',
                    'rating',
                    'is_external',
                    'created_at',
                    'updated_at',
                    'author_name',
                ],
            ],
        ]);
    }

}
