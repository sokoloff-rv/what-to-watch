<?php

namespace App\Services\MovieService;

use Illuminate\Support\Facades\Http;
use App\DTO\FilmData;

class MovieAcademyRepository implements MovieRepositoryInterface
{
    private string $baseUrl = 'http://guide.phpdemo.ru/api/films/';

    public function findMovieById(string $imdbId): ?FilmData
    {
        $response = Http::get($this->baseUrl . $imdbId);

        $movieData = $response->json();

        $filmData = new FilmData();
        $filmData->name = $movieData['name'];
        $filmData->poster_image = $movieData['poster'];
        $filmData->preview_image = $movieData['icon'];
        $filmData->background_image = $movieData['background'];
        $filmData->video_link = $movieData['video'];
        $filmData->preview_video_link = $movieData['preview'];
        $filmData->description = $movieData['desc'];
        $filmData->director = $movieData['director'];
        $filmData->released = (int) $movieData['released'];
        $filmData->run_time = (int) $movieData['run_time'];
        $filmData->imdb_id = $movieData['imdb_id'];
        $filmData->starring = $movieData['actors'];
        $filmData->genre = $movieData['genres'];

        return $filmData;
    }
}
