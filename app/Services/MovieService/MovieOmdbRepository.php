<?php

namespace App\Services\MovieService;

use GuzzleHttp\Client;

class MovieOmdbRepository implements MovieRepositoryInterface
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl = 'http://www.omdbapi.com/';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('services.omdb.api_key');
    }

    public function findMovieById(string $imdbId): ?array
    {
        $response = $this->client->request('GET', $this->baseUrl, [
            'query' => [
                'apikey' => $this->apiKey,
                'i' => $imdbId,
            ],
        ]);

        $movieData = json_decode($response->getBody()->getContents(), true);

        $formattedMovieData = [
            'name' => $movieData['Title'],
            'poster_image' => $movieData['Poster'],
            'preview_image' => null,
            'background_image' => null,
            'background_color' => null,
            'video_link' => null,
            'preview_video_link' => null,
            'description' => $movieData['Plot'],
            'director' => $movieData['Director'],
            'released' => (int) $movieData['Year'],
            'run_time' => (int) $movieData['Runtime'],
            'rating' => (float) $movieData['imdbRating'],
            'scores_count' => (int) str_replace(',', '', $movieData['imdbVotes']),
            'imdb_id' => $movieData['imdbID'],
            'starring' => array_map('trim', explode(',', $movieData['Actors'])),
            'genre' => array_map('trim', explode(',', $movieData['Genre'])),
        ];

        return $formattedMovieData;
    }

}
