<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends BaseResponse
{
    public function __construct(?array $data = null, int $statusCode = Response::HTTP_OK)
    {
        parent::__construct($data, $statusCode);
    }

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
