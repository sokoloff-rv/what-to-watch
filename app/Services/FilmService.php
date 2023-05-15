<?php

namespace App\Services;

use App\Models\Film;

class FilmService
{
    public function __construct(
        private ActorService $actorService,
        private GenreService $genreService
    ) {
        $this->actorService = $actorService;
        $this->genreService = $genreService;
    }

    public function createFromData(array $data): Film
    {
        $film = Film::firstOrCreate(
            ['imdb_id' => $data['imdb_id']],
            ['status' => Film::STATUS_MODERATE]
        );
        $this->saveFilm($film, $data);
        return $film;
    }

    public function updateFromData(array $data): Film
    {
        $film = Film::firstWhere('imdb_id', $data['imdb_id']);
        if ($film) {
            $this->saveFilm($film, $data);
        }
        return $film;
    }

    private function saveFilm(Film $film, array $data): void
    {
        $film->fill($data);
        $film->status = Film::STATUS_MODERATE;
        $film->save();

        if (isset($data['starring'])) {
            $this->actorService->syncActors($film, $data['starring']);
        }

        if (isset($data['genre'])) {
            $this->genreService->syncGenres($film, $data['genre']);
        }
    }
}
