<?php

namespace App\Services;

use App\Models\Actor;
use App\Models\Film;

class ActorService
{
    public function syncActors(Film $film, array $actorsNames): void
    {
        $film->actors()->detach();
        foreach ($actorsNames as $actorName) {
            $actor = Actor::firstOrCreate(['name' => $actorName]);
            $film->actors()->attach($actor);
        }
    }
}
