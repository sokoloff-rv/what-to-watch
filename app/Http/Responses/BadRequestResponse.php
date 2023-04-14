<?php

namespace App\Http\Responses;

class BadRequestResponse extends BaseResponse
{
    private string $errorMessage;

    public function __construct(string $errorMessage = 'Неправильное тело запроса')
    {
        $this->errorMessage = $errorMessage;
        parent::__construct(null, Response::HTTP_BAD_REQUEST);
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
