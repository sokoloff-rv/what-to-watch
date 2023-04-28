<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Получение данных о пользователе
     *
     * @return BaseResponse
     */
    public function show(): BaseResponse
    {
        try {
            $user = Auth::user();
            return new SuccessResponse([
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Обновление данных о пользователе
     *
     * @return BaseResponse
     */
    public function update(Request $request): BaseResponse
    {
        if (false) {
            return new FailResponse();
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
