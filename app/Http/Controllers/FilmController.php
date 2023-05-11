<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilmRequest;
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Jobs\CreateFilmJob;
use App\Models\Actor;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class FilmController extends Controller
{
    /**
     * Получение списка фильмов
     *
     * @return BaseResponse
     */
    public function index(FilmRequest $request): BaseResponse
    {
        try {
            $pageQuantity = 8;
            $page = $request->query('page');
            $genre = $request->query('genre');
            $status = $request->query('status', Film::STATUS_READY);
            $order_by = $request->query('order_by', Film::ORDER_BY_RELEASED);
            $order_to = $request->query('order_to', Film::ORDER_TO_DESC);

            if (Gate::denies('view-films-with-status', $status)) {
                return new FailResponse("У вас нет разрешения на просмотр фильмов статусе $status", Response::HTTP_FORBIDDEN);
            }

            $films = Film::query()
                ->when($genre, function ($query, $genre) {
                    return $query->whereHas('genres', function ($query) use ($genre) {
                        $query->where('name', $genre);
                    });
                })
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->orderBy($order_by, $order_to)
                ->paginate($pageQuantity);

            return new SuccessResponse($films);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Добавление фильма в базу
     *
     * @return BaseResponse
     */
    public function store(StoreFilmRequest $request)
    {
        try {
            $imdbId = $request->input('imdb_id');

            $data = [
                'imdb_id' => $imdbId,
                'status' => Film::STATUS_PENDING,
            ];

            CreateFilmJob::dispatch($data);

            return new SuccessResponse(null, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Получение информации о фильме
     *
     * @return BaseResponse
     */
    public function show(Film $film): BaseResponse
    {
        try {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if ($user) {
                $isFavorite = $user->favoriteFilms()->where('film_id', $film->id)->exists();
                $film->is_favorite = $isFavorite;
            }

            return new SuccessResponse($film);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Редактирование фильма
     *
     * @return BaseResponse
     */
    public function update(UpdateFilmRequest $request, Film $film): BaseResponse
    {
        try {
            $film->update($request->validated());

            if ($request->has('starring')) {
                $actorsNames = $request->input('starring');
                $actorIdentifiers = [];
                foreach ($actorsNames as $actorName) {
                    $actor = Actor::firstOrCreate(['name' => $actorName]);
                    $actorIdentifiers[] = $actor->id;
                }
                $film->actors()->sync($actorIdentifiers);
            }

            if ($request->has('genre')) {
                $genresNames = $request->input('genre');
                $genreIdentifiers = [];
                foreach ($genresNames as $genreName) {
                    $genre = Genre::firstOrCreate(['name' => $genreName]);
                    $genreIdentifiers[] = $genre->id;
                }
                $film->genres()->sync($genreIdentifiers);
            }

            return new SuccessResponse($film);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
