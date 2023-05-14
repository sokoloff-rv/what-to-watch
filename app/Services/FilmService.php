<?php

namespace App\Services;

use App\Models\Film;

class FilmService
{
    protected $actorService;
    protected $genreService;

    public function __construct(ActorService $actorService, GenreService $genreService)
    {
        $this->actorService = $actorService;
        $this->genreService = $genreService;
    }

    public function createFromData(array $data): Film
    {
        $film = new Film();

        $film->fill($data);
        $film->status = Film::STATUS_MODERATE;

        $film->save();

        if (isset($data['starring'])) {
            $this->actorService->syncActors($film, $data['starring']);
        }

        if (isset($data['genre'])) {
            $this->genreService->syncGenres($film, $data['genre']);
        }

        return $film;
    }
}
