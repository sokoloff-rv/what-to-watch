<?php

namespace App\Services\MovieService;

use App\DTO\FilmData;
use Illuminate\Support\Facades\Http;

class MovieAcademyRepository implements MovieRepositoryInterface
{
    private string $baseUrl = 'http://guide.phpdemo.ru/api/films/';

    public function findMovieById(string $imdbId): ?array
    {
        $response = Http::get($this->baseUrl . $imdbId);

        if (!$response->successful()) {
            return null;
        }

        $movieData = $response->json();

        $filmData = new FilmData(
            $movieData['name'] ?? null,
            $movieData['desc'] ?? null,
            $movieData['director'] ?? null,
            (int) ($movieData['released'] ?? 0),
            (int) ($movieData['run_time'] ?? 0),
            $movieData['imdb_id'] ?? null,
            $movieData['actors'] ?? null,
            $movieData['genres'] ?? null
        );

        $filmData->poster_image = $movieData['poster'] ?? null;
        $filmData->preview_image = $movieData['icon'] ?? null;
        $filmData->background_image = $movieData['background'] ?? null;
        $filmData->video_link = $movieData['video'] ?? null;
        $filmData->preview_video_link = $movieData['preview'] ?? null;

        return $filmData->toArray();
    }

}
