<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FailResponse extends BaseResponse
{
    private string $errorMessage;

    public function __construct(
        string $errorMessage = null,
        int $statusCode = null, Throwable $exception = null
    ) {
        if ($exception) {
            $this->errorMessage = $errorMessage ?? $exception->getMessage();
            $statusCode = $statusCode ?? $exception->getCode();
        } else {
            $this->errorMessage = $errorMessage ?? 'Ошибка в теле запроса';
            $statusCode = $statusCode ?? Response::HTTP_BAD_REQUEST;
        }

        parent::__construct(null, $statusCode);
    }

    /**
     * Формирование содержимого ответа с текстом ошибки
     *
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'error' => $this->errorMessage,
        ];
    }
}
