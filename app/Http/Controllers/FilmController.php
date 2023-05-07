<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilmRequest;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Film;
use App\Services\MovieService\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
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
    public function store(Request $request, MovieService $movieService)
    {
        try {
            $validatedData = $request->validate([
                'imdb_id' => [
                    'required',
                    'string',
                    Rule::unique(Film::class),
                ],
            ]);

            $imdbId = $validatedData['imdb_id'];

            $movieData = $movieService->getMovie($imdbId);
            if (!$movieData) {
                return new FailResponse("Такой фильм не найден.", Response::HTTP_NOT_FOUND);
            }

            $film = Film::createFromData($movieData);

            return new SuccessResponse($film, Response::HTTP_CREATED);
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
    public function update(Request $request, Film $film): BaseResponse
    {
        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
