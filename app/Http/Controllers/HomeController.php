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
        $cars = Car::with([
            'brand',
            'model',
            'city',
            'colors',
            'photos' => function ($query) {
                $query->orderBy('sort_order');
            }
        ])->get();
        return Inertia::render('Home', [
            'cars' => $cars
        ]);
    }
}
