<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\UnauthResponse;

class UserController extends Controller
{
    /**
     * Получение данных о пользователе
     *
     * @return BaseResponse
     */
    public function show(): BaseResponse
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
     * Обновление данных о пользователе
     *
     * @return BaseResponse
     */
    public function update(Request $request): BaseResponse
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
}
