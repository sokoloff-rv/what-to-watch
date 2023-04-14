<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class NotFoundResponse extends BaseResponse
{
    private string $errorMessage;

    public function __construct(string $errorMessage = 'Объект не найден')
    {
        $this->errorMessage = $errorMessage;
        parent::__construct(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Формирование содержимого ответа с текстом ошибки
     *
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'error' => $this->errorMessage
        ];
    }
}
