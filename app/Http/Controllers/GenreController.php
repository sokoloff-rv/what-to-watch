<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;
use App\Http\Requests\GenreRequest;

class GenreController extends Controller
{
    /**
     * Получение списка жанров.
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        try {
            $genres = Genre::all();

            return new SuccessResponse($genres);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Редактирование жанра.
     *
     * @return BaseResponse
     */
    public function update(GenreRequest $request, Genre $genre): BaseResponse
    {
        try {
            $genre->update([
                'name' => $request->input('name'),
            ]);

            return new SuccessResponse($genre);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
