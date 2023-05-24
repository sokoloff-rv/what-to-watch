<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use App\Models\Comment;
use App\Models\Film;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Получение отзывов к фильму.
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
     * Добавление отзыва к фильму.
     *
     * @return BaseResponse
     */
    public function store(CommentRequest $request, Film $film): BaseResponse
    {
        try {
            /** @var User|null $user */
            $user = Auth::user();

            $comment = $film->comments()->create([
                'comment_id' => $request->get('comment_id', null),
                'text' => $request->input('text'),
                'rating' => $request->input('rating'),
                'user_id' => $user->id,
            ]);

            return new SuccessResponse($comment);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Редактирование отзыва к фильму.
     *
     * @return BaseResponse
     */
    public function update(CommentRequest $request, Comment $comment): BaseResponse
    {
        if (Gate::denies('comment-edit', $comment)) {
            return new FailResponse('Недостаточно прав.', Response::HTTP_FORBIDDEN);
        }

        try {
            $comment->update([
                'text' => $request->input('text'),
                'rating' => $request->input('rating'),
            ]);

            return new SuccessResponse($comment);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Удаление отзыва к фильму.
     *
     * @return BaseResponse
     */
    public function destroy(Request $request, Comment $comment): BaseResponse
    {
        if (Gate::denies('comment-delete', $comment)) {
            return new FailResponse('Недостаточно прав.', Response::HTTP_FORBIDDEN);
        }

        try {
            if ($comment->children()->count() > 0) {
                $comment->children()->delete();
            }
            $comment->delete();

            return new SuccessResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
