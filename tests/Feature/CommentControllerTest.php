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

    private function getTypicalCommentStructure(): array
    {
        return [
            'data' => [
                'id',
                'parent_id',
                'film_id',
                'text',
                'rating',
                'user_id',
                'author_name',
            ],
        ];
    }

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
                    'parent_id',
                    'film_id',
                    'text',
                    'rating',
                    'user_id',
                    'author_name',
                    'is_external',
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
        $response->assertJsonStructure($this->getTypicalCommentStructure());
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
        $response->assertJsonStructure($this->getTypicalCommentStructure());
        $response->assertJsonPath('data.parent_id', $parentComment->id);
    }

    public function testUpdateUnauthorized()
    {
        $comment = Comment::factory()->create();

        $data = [
            'text' => 'Обновленный текст тестового комментария достаточной длины для прохождения валидации.',
            'rating' => 9,
        ];

        $response = $this->patchJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testUpdateForbidden()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $data = [
            'text' => 'Обновленный текст тестового комментария достаточной длины для прохождения валидации.',
            'rating' => 9,
        ];

        $response = $this->actingAs($user)->patchJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testUpdateSuccess()
    {
        $comment = Comment::factory()->create();

        $data = [
            'text' => 'Обновленный текст тестового комментария достаточной длины для прохождения валидации.',
            'rating' => 9,
        ];

        $response = $this->actingAs($comment->user)->patchJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->getTypicalCommentStructure());
        $response->assertJsonPath('data.text', $data['text']);
        $response->assertJsonPath('data.rating', $data['rating']);
    }

    public function testUpdateValidationError()
    {
        $comment = Comment::factory()->create();

        $data = [
            'text' => 'Короткий неправильный комментарий.',
            'rating' => 11,
        ];

        $response = $this->actingAs($comment->user)->patchJson("/api/comments/{$comment->id}", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Переданные данные не корректны.',
        ]);
        $response->assertJsonValidationErrors([
            'text',
            'rating',
        ]);
    }

    public function testDestroyUnauthorized()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testDestroyForbidden()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testDestroySuccess()
    {
        $comment = Comment::factory()->create();

        $response = $this->actingAs($comment->user)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

}
