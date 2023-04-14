<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\UnauthResponse;
use App\Http\Responses\NotFoundResponse;

class GenreController extends Controller
{
    /**
     * Получение списка жанров
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new BadRequestResponse();
        }
    }

    /**
     * Редактирование жанра
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
