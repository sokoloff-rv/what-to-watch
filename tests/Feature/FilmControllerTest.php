<?php

namespace Tests\Feature;

use App\Jobs\CreateFilmJob;
use App\Models\Film;
use App\Models\Genre;
use App\Models\User;
use App\Services\MovieService\MovieRepositoryInterface;
use App\Services\MovieService\MovieService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FilmControllerTest extends TestCase
{
    use RefreshDatabase;

    private function getTypicalFilmStructure(): array
    {
        return [
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
        ];
    }

    public function testIndexAllFilms()
    {
        Film::factory()->count(10)->create(['status' => Film::STATUS_READY]);

        $response = $this->get('/api/films');

        $response->assertStatus(Response::HTTP_OK);
        $responseData = json_decode($response->getContent(), true);
        $response->assertJsonCount(8, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => $this->getTypicalFilmStructure(),
            ],
            'current_page',
            'first_page_url',
            'next_page_url',
            'prev_page_url',
            'per_page',
            'total',
        ]);
    }

    public function testIndexFilteredByGenre()
    {
        $genre = Genre::factory()->create();
        Film::factory()->count(5)->create(['status' => Film::STATUS_READY])->each(function ($film) use ($genre) {
            $film->genres()->attach($genre);
        });

        $response = $this->get('/api/films?genre=' . $genre->name);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
    }

    public function testIndexFilteredByStatusForUser()
    {
        $user = User::factory()->create();

        Film::factory()->count(3)->create(['status' => Film::STATUS_PENDING]);
        Film::factory()->count(3)->create(['status' => Film::STATUS_MODERATE]);

        $response = $this->actingAs($user)->get('/api/films?status=' . Film::STATUS_PENDING);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'У вас нет разрешения на просмотр фильмов статусе ' . Film::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)->get('/api/films?status=' . Film::STATUS_MODERATE);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'У вас нет разрешения на просмотр фильмов статусе ' . Film::STATUS_MODERATE,
        ]);
    }

    public function testIndexFilteredByStatusForModerator()
    {
        $moderator = User::factory()->moderator()->create();

        Film::factory()->count(3)->create(['status' => Film::STATUS_PENDING]);
        Film::factory()->count(3)->create(['status' => Film::STATUS_MODERATE]);

        $response = $this->actingAs($moderator)->get('/api/films?status=' . Film::STATUS_PENDING);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');

        $response = $this->actingAs($moderator)->get('/api/films?status=' . Film::STATUS_MODERATE);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
    }

    public function testIndexFilmsOrderedByReleased()
    {
        Film::factory()->count(5)->create(['status' => Film::STATUS_READY]);

        $response = $this->get('/api/films?order_by=' . Film::ORDER_BY_RELEASED . '&order_to=desc');

        $response->assertStatus(Response::HTTP_OK);
        $responseData = json_decode($response->getContent(), true)['data'];

        for ($i = 1; $i < count($responseData); $i++) {
            $this->assertTrue($responseData[$i - 1][Film::ORDER_BY_RELEASED] >= $responseData[$i][Film::ORDER_BY_RELEASED]);
        }
    }

    public function testIndexFilmsOrderedByRating()
    {
        Film::factory()->count(5)->create(['status' => Film::STATUS_READY]);

        $response = $this->get('/api/films?order_by=' . Film::ORDER_BY_RATING . '&order_to=desc');

        $response->assertStatus(Response::HTTP_OK);
        $responseData = json_decode($response->getContent(), true)['data'];

        for ($i = 1; $i < count($responseData); $i++) {
            $this->assertTrue($responseData[$i - 1][Film::ORDER_BY_RATING] >= $responseData[$i][Film::ORDER_BY_RATING]);
        }
    }

    public function testStoreUnauthorized()
    {
        $data = [
            'imdb_id' => 'tt0111161',
        ];

        $response = $this->postJson("/api/films", $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testStoreAuthorized()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $data = [
            'imdb_id' => 'tt0111161',
        ];

        $response = $this->actingAs($user)->postJson("/api/films", $data);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testStoreModerator()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_MODERATOR,
        ]);

        $imdbId = 'tt0111161';

        $data = [
            'imdb_id' => $imdbId,
        ];

        Queue::fake();

        $newMovie = Film::factory()->make([
            'imdb_id' => $imdbId,
        ])->toArray();

        $mockMovieRepository = Mockery::mock(MovieRepositoryInterface::class);
        $mockMovieRepository->shouldReceive('findMovieById')
            ->with($imdbId)
            ->once()
            ->andReturn($newMovie);
        $movieService = new MovieService($mockMovieRepository);

        $this->app->instance(MovieService::class, $movieService);

        $response = $this->actingAs($user)->postJson("/api/films", $data);

        Queue::assertPushed(function (CreateFilmJob $job) use ($imdbId) {
            return $job->data['imdb_id'] === $imdbId;
        });

        Queue::assertPushed(function (CreateFilmJob $job) use ($imdbId, $movieService) {
            $this->assertEquals($imdbId, $job->data['imdb_id']);
            $job->handle($movieService);

            return true;
        });

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('films', [
            'imdb_id' => $imdbId,
        ]);

        Mockery::close();
    }

    public function testStoreFilmAlreadyExists()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_MODERATOR,
        ]);

        $imdbId = 'tt0111161';

        $film = Film::factory()->create([
            'imdb_id' => $imdbId,
        ]);

        $data = [
            'imdb_id' => $imdbId,
        ];

        $mockMovieService = Mockery::mock(MovieService::class);

        $this->app->instance(MovieService::class, $mockMovieService);

        $response = $this->actingAs($user)->postJson("/api/films", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Переданные данные не корректны.',
        ]);
        $response->assertJsonValidationErrors([
            'imdb_id',
        ]);

        Mockery::close();
    }

    public function testStoreValidationError()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_MODERATOR,
        ]);

        $data = [
            'imdb_id' => 'Невалидный imdb_id',
        ];

        $mockMovieService = Mockery::mock(MovieService::class);
        $this->app->instance(MovieService::class, $mockMovieService);

        $response = $this->actingAs($user)->postJson("/api/films", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Переданные данные не корректны.',
        ]);
        $response->assertJsonValidationErrors([
            'imdb_id',
        ]);

        Mockery::close();
    }

    public function testShowFilm()
    {
        $film = Film::factory()->create(['status' => Film::STATUS_READY]);

        $response = $this->get("/api/films/{$film->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => $this->getTypicalFilmStructure(),
        ]);
    }

    public function testShowFavoriteForAuthorized()
    {
        $user = User::factory()->create();
        $film = Film::factory()->create(['status' => Film::STATUS_READY]);

        $user->favoriteFilms()->attach($film);

        $response = $this->actingAs($user)->get("/api/films/{$film->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => array_merge($this->getTypicalFilmStructure(), ['is_favorite']),
        ]);
        $response->assertJsonPath('data.is_favorite', true);
    }

    public function testShowFavoriteForGuest()
    {
        $film = Film::factory()->create(['status' => Film::STATUS_READY]);

        $response = $this->get("/api/films/{$film->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => $this->getTypicalFilmStructure(),
        ]);
        $response->assertJsonMissing(['data' => ['is_favorite']]);
    }

    public function testUpdateUnauthorized()
    {
        $film = Film::factory()->create(['status' => Film::STATUS_PENDING]);

        $newName = 'Новое название фильма';
        $newImdbId = 'tt1234567';
        $newStatus = Film::STATUS_READY;

        $response = $this->patchJson("/api/films/{$film->id}", [
            'name' => $newName,
            'imdb_id' => $newImdbId,
            'status' => $newStatus,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testUpdateAuthorized()
    {
        $user = User::factory()->create();
        $film = Film::factory()->create(['status' => Film::STATUS_PENDING]);

        $newName = 'Новое название фильма';
        $newImdbId = 'tt1234567';
        $newStatus = Film::STATUS_READY;

        $response = $this->actingAs($user)
            ->patchJson("/api/films/{$film->id}", [
                'name' => $newName,
                'imdb_id' => $newImdbId,
                'status' => $newStatus,
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'Запрос требует аутентификации.',
        ]);
    }

    public function testUpdateModerator()
    {
        $user = User::factory()->moderator()->create();
        $film = Film::factory()->create(['status' => Film::STATUS_PENDING]);

        $newName = 'Новое название фильма';
        $newImdbId = 'tt1234567';
        $newStatus = Film::STATUS_READY;

        $response = $this->actingAs($user)
            ->patchJson("/api/films/{$film->id}", [
                'name' => $newName,
                'imdb_id' => $newImdbId,
                'status' => $newStatus,
            ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $film->id,
                'name' => $newName,
                'imdb_id' => $newImdbId,
                'status' => $newStatus,
            ],
        ]);
    }

    public function testUpdateValidationError()
    {
        $film = Film::factory()->create();
        $user = User::factory()->moderator()->create();

        $invalidData = [
            'name' => '',
            'poster_image' => str_repeat('a', 256),
            'preview_image' => str_repeat('a', 256),
            'background_image' => str_repeat('a', 256),
            'background_color' => str_repeat('a', 10),
            'video_link' => str_repeat('a', 256),
            'preview_video_link' => str_repeat('a', 256),
            'description' => str_repeat('a', 1001),
            'director' => str_repeat('a', 256),
            'starring' => 'Не массив',
            'genre' => 'Не массив',
            'run_time' => 'Не число',
            'released' => 'Не число',
            'imdb_id' => 'Невалидный imdb_id',
            'status' => 'Несуществующий статус',
        ];

        $response = $this->actingAs($user)
            ->patchJson("/api/films/{$film->id}", $invalidData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'name',
            'poster_image',
            'preview_image',
            'background_image',
            'background_color',
            'video_link',
            'preview_video_link',
            'description',
            'director',
            'starring',
            'genre',
            'run_time',
            'released',
            'imdb_id',
            'status',
        ]);
    }

}
