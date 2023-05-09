<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use App\Services\MovieService\MovieOmdbRepository;
use App\Services\MovieService\MovieService;

$client = new Client();
$repository = new MovieOmdbRepository($client);
$service = new MovieService($repository);

$movie = $service->getMovie('tt13157618');
var_dump($movie['Title']);
