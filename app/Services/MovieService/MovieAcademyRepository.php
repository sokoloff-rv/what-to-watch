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

        $movieData = $response->json();

        $filmData = new FilmData(
            $movieData['name'],
            $movieData['desc'],
            $movieData['director'],
            (int) $movieData['released'],
            (int) $movieData['run_time'],
            $movieData['imdb_id'],
            $movieData['actors'],
            $movieData['genres']
        );

        $filmData->poster_image = $movieData['poster'];
        $filmData->preview_image = $movieData['icon'];
        $filmData->background_image = $movieData['background'];
        $filmData->video_link = $movieData['video'];
        $filmData->preview_video_link = $movieData['preview'];

        return $filmData->toArray();
    }

}
