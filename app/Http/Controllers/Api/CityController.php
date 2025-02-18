<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Storages\CarCityStorage;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    private CarCityStorage $carCityStorage;

    public function __construct(CarCityStorage $carCityStorage)
    {
        $this->carCityStorage = $carCityStorage;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->carCityStorage->allActive());
    }
}
