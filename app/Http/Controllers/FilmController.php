<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\UnauthResponse;
use App\Http\Responses\NotFoundResponse;

class FilmController extends Controller
{
    /**
     * Получение списка фильмов
     *
     * @return BaseResponse
     */
    public function index(Request $request, ?int $page, ?string $genre, ?string $status, ?string $order_by, ?string $order_to): BaseResponse
    {
        //
        return new SuccessResponse();
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
            return new BadRequestResponse();
        }
    }

    /**
     * Получение информации о фильме
     *
     * @return BaseResponse
     */
    public function show(string $id): BaseResponse
    {
        if (!$id) {
            return new NotFoundResponse();
        }
        //
        return new SuccessResponse();
    }

    /**
     * Редактирование фильма
     *
     * @return BaseResponse
     */
    public function update(Request $request, string $id): BaseResponse
    {
        if (/* проверка авторизации пользователя */) {
            return new UnauthResponse();
        }

        if (!$id) {
            return new NotFoundResponse();
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new BadRequestResponse();
        }
    }
}
