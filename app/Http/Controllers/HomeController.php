<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Inertia\Inertia;
use Inertia\Response;

class HomeController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        //Получаем машины со всеми необходимиым данныеми
        $cars = Car::with([
            'brand',
            'model',
            'city',
            'colors',
            'photos' => function ($query) {
                $query->orderBy('sort_order');
            }
        ])->get();

        // Модифицируем VIN каждого автомобиля
        $cars = $cars->map(function ($car) {
            $car->vin = Car::maskVin($car->vin); // Используем статический метод
            return $car;
        });

        //Рендерем вьюшку Home с пропсами машин
        return Inertia::render('Home', [
            'cars' => $cars
        ]);
    }
}
