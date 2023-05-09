<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use App\Services\MovieService\MovieApiClient;
use App\Services\MovieService\MovieRepository;
use App\Services\MovieService\MovieService;

$client = new Client();
$apiClient = new MovieApiClient($client);
$repository = new MovieRepository($apiClient);
$service = new MovieService($repository);

$movie = $service->getMovie('tt13157618');
echo $movie["Title"];
