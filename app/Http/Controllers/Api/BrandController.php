<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Storages\CarBrandStorage;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    private CarBrandStorage $carBranStorage;

    public function __construct(CarBrandStorage $carBranStorage)
    {
        $this->carBranStorage = $carBranStorage;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->carBranStorage->allActive());
    }
}
