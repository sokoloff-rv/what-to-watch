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

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(MovieService $movieService)
    {
        $imdbId = $this->data['imdb_id'];
        Log::info('CreateFilmJob начала выполняться для фильма с imdbId ' . $imdbId);

        $movieData = $movieService->getMovie($imdbId);

        if ($movieData) {
            $movieData['status'] = $this->data['status'];
            Film::createFromData($movieData);
        }

        Log::info('CreateFilmJob успешно выполнена для фильма с imdbId ' . $imdbId);
    }
}
