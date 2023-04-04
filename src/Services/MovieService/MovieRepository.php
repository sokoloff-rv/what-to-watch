<?php

namespace WhatToWatch\Services\MovieService;

class MovieRepository
{
    private MovieApiClient $apiClient;

    /**
     * Конструктор класса MovieRepository
     * 
     * @param MovieApiClient $apiClient Клиент для работы с API сервиса, предоставляющего информацию по фильмам
     */
    public function __construct(MovieApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Поиск фильма по его IMDB ID
     *
     * @param string $imdbId IMDB ID фильма
     * @return array|null Возвращает массив с информацией о фильме или null, если фильм не найден
     */
    public function findMovieById(string $imdbId): ?array
    {
        $response = $this->apiClient->sendRequest($imdbId);

        return json_decode($response->getBody()->getContents(), true);
    }
}
