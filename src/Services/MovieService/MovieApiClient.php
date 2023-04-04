<?php

namespace WhatToWatch\Services\MovieService;

class MovieApiClient
{
    private \GuzzleHttp\Client $client;
    private string $apiKey = 'a471b1ee';
    private string $baseUrl = 'http://www.omdbapi.com/';

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function sendRequest(string $imdbId): \GuzzleHttp\Psr7\Response
    {
        return $this->client->request('GET', $this->baseUrl, ['query' =>
            [
                'apikey' => $this->apiKey,
                'i' => $imdbId,
            ]
        ]);
    }
}
