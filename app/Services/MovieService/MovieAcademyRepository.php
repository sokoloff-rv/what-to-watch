<?php

namespace App\Services\MovieService;

use GuzzleHttp\Client;

class MovieAcademyRepository implements MovieRepositoryInterface
{
    private Client $client;
    private string $baseUrl = 'http://guide.phpdemo.ru/api/films/';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findMovieById(string $imdbId): ?array
    {
        $response = $this->client->request('GET', $this->baseUrl.$imdbId);

        $movieData = json_decode($response->getBody()->getContents(), true);

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
