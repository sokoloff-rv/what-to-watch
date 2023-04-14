<?php

namespace App\Http\Responses;

class NoContentResponse extends BaseResponse
{
    public function __construct()
    {
        parent::__construct(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Формирование содержимого успешного ответа без данных
     *
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return null;
    }
}
