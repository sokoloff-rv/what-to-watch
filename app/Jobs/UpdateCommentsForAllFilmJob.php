<?php

namespace App\Jobs;

use App\Models\Film;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCommentsForAllFilmJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Обработка задачи по обновлению комментариев для всех фильмов.
     *
     * @return void
     */
    public function handle()
    {
        $delayTime = 0;
        $intervalInSeconds = 3;

        Film::all()->each(function ($film) use (&$delayTime, $intervalInSeconds) {
            UpdateCommentsForFilmJob::dispatch($film)->delay(now()->addSeconds($delayTime));
            $delayTime += $intervalInSeconds;
        });
    }
}
