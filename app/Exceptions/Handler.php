<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
        ? response()->json([
            'message' => 'Запрос требует аутентификации.',
        ], Response::HTTP_UNAUTHORIZED)
        : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    public function render($request, Throwable $exception)
    {
        if (($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) && $request->expectsJson()) {
            return response()->json([
                'message' => 'Запрашиваемая страница не существует.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ValidationException && $request->expectsJson()) {
            $errors = $exception->errors();
            $response = [
                'message' => 'Переданные данные не корректны.',
                'errors' => $errors,
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return parent::render($request, $exception);
    }
}
