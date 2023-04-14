<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\NoContentResponse;

class AuthController extends Controller
{
    /**
     * Выполняет авторизацию пользователя в сервисе
     *
     * @return BaseResponse
     */
    public function login(Request $request): BaseResponse
    {
        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new BadRequestResponse();
        }
    }

    /**
     * Выполняет выход пользователя из сервиса
     *
     * @return BaseResponse
     */
    public function logout(): BaseResponse
    {
        //
        return new NoContentResponse();
    }
}
