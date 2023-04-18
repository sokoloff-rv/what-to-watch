<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends BaseResponse
{
    /**
     * Формирование содержимого успешного ответа
     *
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'data' => $this->prepareData(),
        ];
    }
}
