<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
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
            $comments = $film->comments()->get();
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
        try {
            $validatedData = $request->validate([
                'text' => 'required|string|min:50|max:400',
                'rating' => 'required|integer|min:1|max:10',
            ]);

            $comment = $film->comments()->create([
                'text' => $validatedData['text'],
                'rating' => $validatedData['rating'],
                'user_id' => Auth::user()->id,
            ]);

            return new SuccessResponse($comment);
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
        if (Gate::denies('comment-edit', $comment)) {
            return new FailResponse('У вас нет разрешения на редактирование этого комментария', Response::HTTP_FORBIDDEN);
        }

        try {
            $validatedData = $request->validate([
                'text' => 'required|string|min:50|max:400',
                'rating' => 'required|integer|min:1|max:10',
            ]);

            $comment->update($validatedData);
            $comment->save();

            return new SuccessResponse($comment);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Удаление отзыва к фильму
     *
     * @return BaseResponse
     */
    public function destroy(Request $request, Comment $comment): BaseResponse
    {
        if (Gate::denies('comment-delete', $comment)) {
            return new FailResponse('У вас нет разрешения на удаление этого комментария', Response::HTTP_FORBIDDEN);
        }

        try {
            $comment->delete();
            return new SuccessResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
