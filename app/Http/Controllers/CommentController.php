<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\UnauthResponse;
use App\Http\Responses\NotFoundResponse;

class CommentController extends Controller
{
    /**
     * Получение отзывов к фильму
     *
     * @return BaseResponse
     */
    public function index(string $filmId): BaseResponse
    {
        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new BadRequestResponse();
        }
    }

    /**
     * Добавление отзыва к фильму
     *
     * @return BaseResponse
     */
    public function store(Request $request, string $filmId): BaseResponse
    {
        if (/* проверка авторизации пользователя */) {
            return new UnauthResponse();
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new BadRequestResponse();
        }
    }

    /**
     * Редактирование отзыва к фильму
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

    /**
     * Удаление отзыва к фильму
     *
     * @return BaseResponse
     */
    public function destroy(string $id): BaseResponse
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
