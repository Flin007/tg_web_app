<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CarCityRepository;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    private CarCityRepository $carCityRepository;

    public function __construct(CarCityRepository $carCityRepository)
    {
        $this->carCityRepository = $carCityRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->carCityRepository->all());
    }
}
