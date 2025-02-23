<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $cars = Car::with([
            'brand',
            'model',
            'city',
            'colors',
            'photos' => function ($query) {
                $query->orderBy('sort_order');
            }
        ])
            ->filter(request(Car::AVAILABLE_FILTERS))
            ->where('is_available', true)
            ->paginate(10)
            ->withQueryString()
            ->through(function ($car) {
                $car->vin = Car::maskVin($car->vin); // Используем статический метод
                return $car;
            });

        return response()->json($cars);
    }
}
