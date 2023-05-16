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

    public function createFromData(array $data, string $nextStatus): Film
    {
        $film = Film::firstOrCreate(
            ['imdb_id' => $data['imdb_id']],
            ['status' => $nextStatus]
        );
        $this->saveFilm($film, $data);
        return $film;
    }

    public function updateFromData(array $data, string $nextStatus): ?Film
    {
        $film = Film::firstWhere('imdb_id', $data['imdb_id']);
        if ($film) {
            $this->saveFilm($film, $data, $nextStatus);
            return $film;
        } else {
            return null;
        }
    }

    private function saveFilm(Film $film, array $data, string $nextStatus): void
    {
        $film->fill($data, $nextStatus);
        $film->status = $nextStatus;
        $film->save();

        if (isset($data['starring'])) {
            $this->actorService->syncActors($film, $data['starring']);
        }

        if (isset($data['genre'])) {
            $this->genreService->syncGenres($film, $data['genre']);
        }
    }

    public function deleteFilm(string $imdbId): void
    {
        $film = Film::firstWhere('imdb_id', $imdbId);
        if ($film) {
            $film->delete();
        }
    }
}
