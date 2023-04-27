<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Получение отзывов к фильму
     *
     * @return BaseResponse
     */
    public function index(Film $film): BaseResponse
    {
        try {
            $comments = $film->comments;
            return new SuccessResponse($comments);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Добавление отзыва к фильму
     *
     * @return BaseResponse
     */
    public function store(Request $request, Film $film): BaseResponse
    {
        if (false) {
            return new FailResponse('Необходима авторизация', Response::HTTP_UNAUTHORIZED);
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Редактирование отзыва к фильму
     *
     * @return BaseResponse
     */
    public function update(Request $request, Comment $comment): BaseResponse
    {
        if (false) {
            return new FailResponse('Необходима авторизация', Response::HTTP_UNAUTHORIZED);
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Удаление отзыва к фильму
     *
     * @return BaseResponse
     */
    public function destroy(Comment $comment): BaseResponse
    {
        if (false) {
            return new FailResponse('Необходима авторизация', Response::HTTP_UNAUTHORIZED);
        }

        try {
            //
            return new SuccessResponse();
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
