<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Promo;
use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\FailResponse;

class PromoController extends Controller
{
    /**
     * Получение текущего промо-фильма
     *
     * @return BaseResponse
     */
    public function index(): BaseResponse
    {
        try {
            $promo = Promo::latest()->first();
            return new SuccessResponse($promo);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

    /**
     * Установка нового промо-фильма
     *
     * @return BaseResponse
     */
    public function store(Request $request, Film $film): BaseResponse
    {
        try {
            $promo = Promo::create(['film_id' => $film->id]);

            return new SuccessResponse($promo);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }
}
