<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\UnauthResponse;

class FavoriteController extends Controller
{
    /**
     * Получение списка избранных фильмов
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        if (/* проверка авторизации пользователя */) {
            return new UnauthResponse();
        }
        //
        return new SuccessResponse();
    }

    /**
     * Добавление фильма в избранное
     *
     * @return BaseResponse
     */
    public function store(Request $request, string $id): BaseResponse
    {
        if (/* проверка авторизации пользователя */) {
            return new UnauthResponse();
        }
        //
        return new SuccessResponse();
    }

    /**
     * Удаление фильма из избранного
     *
     * @return BaseResponse
     */
    public function destroy(string $id): BaseResponse
    {
        if (/* проверка авторизации пользователя */) {
            return new UnauthResponse();
        }
        //
        return new SuccessResponse();
    }
}
