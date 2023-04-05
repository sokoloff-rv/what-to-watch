<?php

namespace WhatToWatch\Services\MovieService;

interface MovieRepositoryInterface
{
    public function findMovieById(string $imdbId);
}
