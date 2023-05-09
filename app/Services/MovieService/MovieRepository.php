<?php

namespace App\Services\MovieService;

class MovieRepository implements MovieRepositoryInterface
{
    private MovieApiClientInterface $apiClient;

    /**
     * Конструктор класса MovieRepository
     *
     * @param  MovieApiClientInterface  $apiClient Клиент для работы с API сервиса, предоставляющего информацию по фильмам
     */
    public function __construct(MovieApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Поиск фильма по его IMDB ID
     *
     * @param  string  $imdbId IMDB ID фильма
     * @return array|null Возвращает массив с информацией о фильме или null, если фильм не найден
     */
    public function findMovieById(string $imdbId): ?array
    {
        $response = $this->apiClient->sendRequest($imdbId);

        return json_decode($response->getBody()->getContents(), true);
    }
}
