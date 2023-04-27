<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;
use Symfony\Component\HttpFoundation\Response;

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
            $genres = Film::all();
            return new SuccessResponse($genres);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Редактирование жанра
     *
     * @return BaseResponse
     */
    public function update(Request $request, string $id): BaseResponse
    {
        if (false) {
            return new FailResponse('Необходима авторизация', Response::HTTP_UNAUTHORIZED);
        }

        if (!$id) {
            return new FailResponse('Объект не найден', Response::HTTP_NOT_FOUND);
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
