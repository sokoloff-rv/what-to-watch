<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use WhatToWatch\Services\MovieService\MovieRepository;
use WhatToWatch\Services\MovieService\MovieService;

$client = new Client;
$repository = new MovieRepository($client);
$service = new MovieService($repository);

$movie = $service->getMovie('tt13157618');
echo $movie["Title"];