<?php

namespace App\Services\MovieService;

use Illuminate\Support\Facades\Http;

class MovieAcademyRepository implements MovieRepositoryInterface
{
    private string $baseUrl = 'http://guide.phpdemo.ru/api/films/';

    public function findMovieById(string $imdbId):  ? array
    {
        $response = Http::get($this->baseUrl . $imdbId);

        $movieData = $response->json();

        $formattedMovieData = [
            'name' => $movieData['name'],
            'poster_image' => $movieData['poster'],
            'preview_image' => $movieData['icon'],
            'background_image' => $movieData['background'],
            'background_color' => null,
            'video_link' => $movieData['video'],
            'preview_video_link' => $movieData['preview'],
            'description' => $movieData['desc'],
            'director' => $movieData['director'],
            'released' => (int) $movieData['released'],
            'run_time' => (int) $movieData['run_time'],
            'imdb_id' => $movieData['imdb_id'],
            'starring' => $movieData['actors'],
            'genre' => $movieData['genres'],
        ];

        return $formattedMovieData;
    }
}
