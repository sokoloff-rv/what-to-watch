<?php

namespace App\Services\MovieService;

class MovieApiClient implements MovieApiClientInterface
{
    private \GuzzleHttp\Client $client;

    private string $apiKey = 'a471b1ee';

    private string $baseUrl = 'http://www.omdbapi.com/';

    /**
     * Конструктор класса MovieApiClient
     *
     * @param  \GuzzleHttp\Client  $client HTTP-клиент Guzzle
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Отправка запроса к сервису OMDB для получения информации о фильме по его IMDB ID
     *
     * @param  string  $imdbId IMDB ID фильма
     * @return \Psr\Http\Message\ResponseInterface Возвращает объект ответа Guzzle с информацией о фильме
     */
    public function sendRequest(string $imdbId): \Psr\Http\Message\ResponseInterface
    {
        return $this->client->request('GET', $this->baseUrl, ['query' => [
            'apikey' => $this->apiKey,
            'i' => $imdbId,
        ],
        ]);
    }
}
