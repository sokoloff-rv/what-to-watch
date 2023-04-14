<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\NotFoundResponse;

class SimilarController extends Controller
{
    /**
     * Получение списка похожих фильмов
     *
     * @return BaseResponse
     */
    public function index(string $id): BaseResponse
    {
        if (!$id) {
            return new NotFoundResponse();
        }
        //
        return new SuccessResponse();
    }
}
