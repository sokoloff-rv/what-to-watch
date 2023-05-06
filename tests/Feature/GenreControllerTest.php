<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        Genre::factory()->count(3)->create();

        $response = $this->get('/api/genres');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);
    }

    public function testUpdateUnauthorized()
    {
        $genre = Genre::factory()->create();
        $newName = 'Новый жанр';

        $response = $this->patchJson("/api/genres/{$genre->id}", [
            'name' => $newName,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testUpdateForbidden()
    {
        $genre = Genre::factory()->create();
        $user = User::factory()->create();
        $newName = 'Новый жанр';

        $response = $this->actingAs($user)
            ->patchJson("/api/genres/{$genre->id}", [
                'name' => $newName,
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testUpdateSuccess()
    {
        $genre = Genre::factory()->create();
        $user = User::factory()->moderator()->create();
        $newName = 'Новый жанр';

        $response = $this->actingAs($user)
            ->patchJson("/api/genres/{$genre->id}", [
                'name' => $newName,
            ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $genre->id,
                'name' => $newName,
            ],
        ]);
    }

    public function testUpdateValidationError()
    {
        $genre = Genre::factory()->create();
        $user = User::factory()->moderator()->create();
        $newName = '';

        $response = $this->actingAs($user)
            ->patchJson("/api/genres/{$genre->id}", [
                'name' => $newName,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Переданные данные не корректны.',
            'errors' => [
                'name' => [
                    'Поле Название обязательно для заполнения.',
                ],
            ],
        ]);
    }
}
