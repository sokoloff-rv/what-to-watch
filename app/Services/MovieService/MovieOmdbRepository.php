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

        return json_decode($response->getBody()->getContents(), true);
    }
}
