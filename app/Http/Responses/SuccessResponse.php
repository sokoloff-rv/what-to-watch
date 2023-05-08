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
        $data = $this->prepareData();

        if (isset($data['data']) && is_array($data['data'])) {
            return [
                'data' => $data['data'],
                'current_page' => $data['current_page'],
                'first_page_url' => $data['first_page_url'],
                'next_page_url' => $data['next_page_url'],
                'prev_page_url' => $data['prev_page_url'],
                'per_page' => $data['per_page'],
                'total' => $data['total'],
            ];
        }

        return [
            'data' => $this->prepareData(),
        ];
    }
}
