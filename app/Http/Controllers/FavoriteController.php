<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use App\Models\Film;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    /**
     * Получение списка избранных фильмов.
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $favoriteFilms = $user->favoriteFilms()->orderBy('created_at', 'desc')->get();

            return new SuccessResponse($favoriteFilms);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Добавление фильма в избранное.
     *
     * @return BaseResponse
     */
    public function store(Film $film): BaseResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if ($user->favoriteFilms()->where('film_id', $film->id)->exists()) {
                return new FailResponse('Фильм уже в избранном', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user->favoriteFilms()->attach($film);

            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Удаление фильма из избранного.
     *
     * @return BaseResponse
     */
    public function destroy(Film $film): BaseResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->favoriteFilms()->where('film_id', $film->id)->exists()) {
                return new FailResponse('Фильм не найден в избранном', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user->favoriteFilms()->detach($film);

            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
