<?php

namespace Tests\Feature;

use App\Jobs\CreateFilmJob;
use App\Models\Film;
use App\Services\MovieService\MovieRepositoryInterface;
use App\Services\MovieService\MovieService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class FilmJobTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFilmJob()
    {
        $imdbId = 'tt0111161';

        $data = [
            'imdb_id' => $imdbId,
            'status' => Film::STATUS_PENDING,
        ];

        $newMovie = Film::factory()->make([
            'imdb_id' => $imdbId,
        ])->toArray();

        $mockMovieRepository = Mockery::mock(MovieRepositoryInterface::class);
        $mockMovieRepository->shouldReceive('findMovieById')
            ->with($imdbId)
            ->once()
            ->andReturn($newMovie);
        $movieService = new MovieService($mockMovieRepository);

        $job = new CreateFilmJob($data);
        $job->handle($movieService);

        $this->assertDatabaseHas('films', [
            'imdb_id' => $imdbId,
        ]);

        Mockery::close();
    }
}
