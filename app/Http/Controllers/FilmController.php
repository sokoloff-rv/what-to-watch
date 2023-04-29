<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class FilmController extends Controller
{
    /**
     * Получение списка фильмов
     *
     * @return BaseResponse
     */
    public function index(Request $request, ?int $page = null, ?string $genre = null, ?string $status = null, ?string $order_by = null, ?string $order_to = null): BaseResponse
    {
        try {
            $pageQuantity = 8;
            $status = $request->query('status', Film::STATUS_READY);

            if (Gate::denies('view-films-with-status', $status)) {
                return new FailResponse("У вас нет разрешения на просмотр фильмов статусе $status", Response::HTTP_FORBIDDEN);
            }

            $films = Film::where('status', $status)->paginate($pageQuantity);
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
    public function store(Request $request): BaseResponse
    {
        try {
            //
            return new SuccessResponse();
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
        if (false) {
            return new FailResponse('Необходима авторизация', Response::HTTP_UNAUTHORIZED);
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
