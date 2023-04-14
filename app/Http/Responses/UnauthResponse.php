<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class UnauthResponse extends BaseResponse
{
    private string $errorMessage;

    public function __construct(string $errorMessage = 'Необходима авторизация')
    {
        $this->errorMessage = $errorMessage;
        parent::__construct(null, Response::HTTP_UNAUTHORIZED);
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
