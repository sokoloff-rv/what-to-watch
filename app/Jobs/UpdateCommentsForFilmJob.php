<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Models\Film;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateCommentsForFilmJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Конструктор класса UpdateCommentsForFilmJob.
     */
    public function __construct(protected Film $film)
    {
    }

    /**
     * Обработка задачи по обновлению комментариев для фильма.
     *
     * @return void
     */
    public function handle(): void
    {
        $lastCommentDate = $this->film->comments()->where('is_external', true)->max('created_at');

        $params = ['imdb_id' => $this->film->imdb_id];
        if ($lastCommentDate) {
            $lastCommentDate = Carbon::parse($lastCommentDate);
            $params['date'] = $lastCommentDate->format('Y-m-d');
        }

        $response = Http::get('http://guide.phpdemo.ru/api/comments', $params);

        if ($response->successful()) {
            $comments = $response->json();

            foreach ($comments as $commentData) {
                $commentDate = Carbon::parse($commentData['date']);
                if ($lastCommentDate && $commentDate->lte($lastCommentDate)) {
                    continue;
                }

                Comment::create([
                    'film_id' => $this->film->id,
                    'text' => $commentData['text'],
                    'created_at' => $commentDate,
                    'is_external' => true,
                ]);
            }
        }
    }

}
