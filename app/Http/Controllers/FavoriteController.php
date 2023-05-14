<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Film;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Получение списка избранных фильмов
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        try {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $favoriteFilms = $user->favoriteFilms()->get();

            return new SuccessResponse($favoriteFilms);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Добавление фильма в избранное
     *
     * @return BaseResponse
     */
    public function store(Film $film): BaseResponse
    {
        try {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $user->favoriteFilms()->attach($film);

            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Удаление фильма из избранного
     *
     * @return BaseResponse
     */
    public function destroy(Film $film): BaseResponse
    {
        try {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $user->favoriteFilms()->detach($film);

            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
