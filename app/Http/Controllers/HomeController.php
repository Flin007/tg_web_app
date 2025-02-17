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
        ])
            ->filter(request(Car::AVAILABLE_FILTERS))
            ->where('is_available', true)
            ->paginate(1)
            ->withQueryString()
            ->through(function ($car) {
                $car->vin = Car::maskVin($car->vin); // Используем статический метод
                return $car;
            });

        //Рендерем вьюшку Home с пропсами машин
        return Inertia::render('Home', [
            'cars' => $cars
        ]);
    }
}
