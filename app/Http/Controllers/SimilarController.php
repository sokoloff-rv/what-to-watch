<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Film;
use Symfony\Component\HttpFoundation\Response;

class SimilarController extends Controller
{
    /**
     * Получение списка фильмов, похожих на данный
     *
     * @param Film $film
     * @return BaseResponse
     */
    public function index(Film $film): BaseResponse
    {
        try {
            $status = Film::STATUS_READY;
            $similarFilmsCount = 4;
            $filmGenres = $film->genres->pluck('id');

            $allSimilarFilms = Film::where('status', $status)
                ->where('id', '!=', $film->id)
                ->whereHas('genres', function ($query) use ($filmGenres) {
                    $query->whereIn('genres.id', $filmGenres);
                })
                ->get();

            $allSimilarFilms = $allSimilarFilms->sortByDesc(function ($similarFilm) use ($filmGenres) {
                return $similarFilm->genres->whereIn('id', $filmGenres)->count();
            });

            $similarFilms = $allSimilarFilms->take($similarFilmsCount);

            if ($similarFilms->isEmpty()) {
                return new SuccessResponse(null, Response::HTTP_NO_CONTENT);
            }

            return new SuccessResponse($similarFilms);
        } catch (\Exception $e) {
            return new FailResponse(null, null, $e);
        }
    }

}
