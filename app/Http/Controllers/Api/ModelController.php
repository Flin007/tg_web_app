<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Storages\CarBrandStorage;
use App\Storages\CarModelStorage;
use Illuminate\Http\JsonResponse;

class ModelController extends Controller
{
    private CarModelStorage $carModelStorage;

    public function __construct(CarModelStorage $carModelStorage)
    {
        $this->carModelStorage = $carModelStorage;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->carModelStorage->allActive());
    }
}
