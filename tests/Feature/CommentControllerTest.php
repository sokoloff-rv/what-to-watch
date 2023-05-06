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

    public function testStoreUnauthorized()
    {
        $film = Film::factory()->create();

        $data = [
            'text' => 'Это текст тестового комментария, с помощью которого осуществляется тестирование функционала комментариев.',
            'rating' => 8,
        ];

        $response = $this->postJson("/api/films/{$film->id}/comments", $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testStoreAuthorized()
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();

        $data = [
            'text' => 'Это текст тестового комментария, с помощью которого осуществляется тестирование функционала комментариев.',
            'rating' => 8,
        ];

        $response = $this->actingAs($user)->postJson("/api/films/{$film->id}/comments", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'parent_id',
                'film_id',
                'text',
                'rating',
                'user_id',
                'author_name',
            ],
        ]);
        $response->assertJsonPath('data.parent_id', null);
    }

    public function testStoreValidationError()
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();

        $data = [
            'text' => 'Короткий неправильный комментарий.',
            'rating' => 11,
        ];

        $response = $this->actingAs($user)->postJson("/api/films/{$film->id}/comments", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Переданные данные не корректны.',
        ]);
        $response->assertJsonValidationErrors([
            'text',
            'rating',
        ]);
    }

    public function testStoreReplyToComment()
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();
        $parentComment = Comment::factory()->create(['film_id' => $film->id]);

        $data = [
            'text' => 'Это текст тестового комментария, с помощью которого осуществляется тестирование функционала комментариев.',
            'rating' => 8,
            'parent_id' => $parentComment->id,
        ];

        $response = $this->actingAs($user)->postJson("/api/films/{$film->id}/comments", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'parent_id',
                'film_id',
                'text',
                'rating',
                'user_id',
                'author_name',
            ],
        ]);
        $response->assertJsonPath('data.parent_id', $parentComment->id);
    }

}
