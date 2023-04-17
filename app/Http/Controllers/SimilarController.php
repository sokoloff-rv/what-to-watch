<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;
use Symfony\Component\HttpFoundation\Response;

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
            return new FailResponse('Объект не найден', Response::HTTP_NOT_FOUND);
        }
        //
        return new SuccessResponse();
    }
}
