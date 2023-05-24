<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexUnauthorized(): void
    {
        $response = $this->getJson('/api/favorite');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testIndexAuthorized(): void
    {
        $user = User::factory()->create();
        $films = Film::factory()->count(3)->create();
        $user->favoriteFilms()->attach($films);

        $response = $this->actingAs($user)->getJson('/api/favorite');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'poster_image',
                    'preview_image',
                    'background_image',
                    'background_color',
                    'video_link',
                    'preview_video_link',
                    'description',
                    'director',
                    'released',
                    'run_time',
                    'rating',
                    'scores_count',
                    'imdb_id',
                    'status',
                    'starring',
                    'genre',
                ],
            ],
        ]);
    }

    public function testStoreUnauthorized(): void
    {
        $film = Film::factory()->create();

        $response = $this->postJson("/api/films/{$film->id}/favorite");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testStoreAuthorized(): void
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/films/{$film->id}/favorite");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('user_favorites', [
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);
    }

    public function testDestroyUnauthorized(): void
    {
        $film = Film::factory()->create();

        $response = $this->deleteJson("/api/films/{$film->id}/favorite");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testDestroyAuthorized(): void
    {
        $user = User::factory()->create();
        $film = Film::factory()->create();
        $user->favoriteFilms()->attach($film);

        $response = $this->actingAs($user)->deleteJson("/api/films/{$film->id}/favorite");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('user_favorites', [
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);
    }
}
