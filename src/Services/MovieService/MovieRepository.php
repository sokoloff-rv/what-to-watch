<?php

namespace WhatToWatch\Services\MovieService;

class MovieRepository
{
    private MovieApiClient $apiClient;

    public function __construct(MovieApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function findMovieById(string $imdbId): ?array
    {
        $response = $this->apiClient->sendRequest($imdbId);

        return json_decode($response->getBody()->getContents(), true);
    }
}
