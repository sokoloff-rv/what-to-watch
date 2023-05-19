<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
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
    public function update(UpdateUserRequest $request): BaseResponse
    {
        try {
            /** @var User|null $user */
            $user = Auth::user();
            $data = [
                'email' => $request->input('email'),
                'name' => $request->input('name'),
            ];

            if ($request->has('password')) {
                $data['password'] = Hash::make($request->input('password'));
            }

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $path = $file->store('avatars', 'local');
                $data['avatar'] = $path;
            }

            $user->update($data);

            return new SuccessResponse([
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
