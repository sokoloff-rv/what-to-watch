<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;

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
            $genres = Genre::all();
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
    public function update(Request $request, Genre $genre): BaseResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $genre->update($validatedData);

            return new SuccessResponse($genre);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
