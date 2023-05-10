<?php

namespace App\Jobs;

use App\Models\Film;
use App\Services\MovieService\MovieService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateFilmJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $imdbId;

    public function __construct(string $imdbId)
    {
        $this->imdbId = $imdbId;
    }

    public function handle(MovieService $movieService)
    {
        Log::info('CreateFilmJob начала выполняться для фильма с imdbId ' . $this->imdbId);

        $movieData = $movieService->getMovie($this->imdbId);

        if ($movieData) {
            Film::createFromData($movieData);
        }

        Log::info('CreateFilmJob успешно выполнена для фильма с imdbId ' . $this->imdbId);
    }
}
