<?php

namespace App\Services\MovieService;

interface MovieApiClientInterface
{
    public function sendRequest(string $imdbId);
}
