<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Storages\CarBranStorage;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    private CarBranStorage $carBranStorage;

    public function __construct(CarBranStorage $carBranStorage)
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
