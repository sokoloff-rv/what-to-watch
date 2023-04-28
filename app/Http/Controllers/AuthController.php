<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Выполняет авторизацию пользователя в сервисе
     *
     * @return BaseResponse
     */
    public function login(LoginRequest $request): BaseResponse
    {
        try {
            if (!Auth::attempt($request->validated())) {
                abort(Response::HTTP_UNAUTHORIZED, trans('auth.failed'));
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return new SuccessResponse([
                'token' => $token,
            ]);
        } catch (\Exception$e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Выполняет выход пользователя из сервиса
     *
     * @return BaseResponse
     */
    public function logout(): BaseResponse
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();
            return new SuccessResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception$e) {
            return new FailResponse(null, null, $e);
        }
    }
}
