<?php

namespace WhatToWatch\Services\MovieService;

interface MovieApiClientInterface
{
    public function sendRequest(string $imdbId);
}
