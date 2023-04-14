<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{
    /**
     * Формирование содержимого успешного ответа
     *
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return $this->prepareData();
    }
}
