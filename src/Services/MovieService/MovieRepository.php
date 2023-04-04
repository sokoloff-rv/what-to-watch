<?php

namespace WhatToWatch\Services\MovieService;

class MovieRepository
{
    private string $apiKey = 'a471b1ee';
    private string $baseUrl = 'http://www.omdbapi.com/';

    public function __construct(private \GuzzleHttp\Client $client)
    {
    }

    public function findMovieById(string $imdbId): ?array
    {
        $response = $this->client->request('GET', $this->baseUrl,
            ['query' =>
                [
                    'apikey' => $this->apiKey,
                    'i' => $imdbId,
                ]
            ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
