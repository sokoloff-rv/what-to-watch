<?php

namespace App\Services\MovieService;

use App\DTO\FilmData;
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

        if (!$response->successful()) {
            return null;
        }

        $movieData = json_decode($response->getBody()->getContents(), true);

        $filmData = new FilmData(
            $movieData['Title'] ?? null,
            $movieData['Plot'] ?? null,
            $movieData['Director'] ?? null,
            (int) ($movieData['Year'] ?? 0),
            (int) ($movieData['Runtime'] ?? 0),
            $movieData['imdbID'] ?? null,
            array_map('trim', explode(',', $movieData['Actors'] ?? '')),
            array_map('trim', explode(',', $movieData['Genre'] ?? ''))
        );

        $filmData->poster_image = $movieData['Poster'] ?? null;
        $filmData->rating = (float) ($movieData['imdbRating'] ?? 0);
        $filmData->scores_count = (int) str_replace(',', '', $movieData['imdbVotes'] ?? '0');

        return $filmData->toArray();
    }

}
