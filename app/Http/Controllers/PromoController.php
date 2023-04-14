<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\UnauthResponse;

class PromoController extends Controller
{
    /**
     * Получение текущего промо-фильма
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        //
        return new SuccessResponse();
    }

    /**
     * Установка нового промо-фильма
     *
     * @return BaseResponse
     */
    public function store(Request $request): BaseResponse
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
