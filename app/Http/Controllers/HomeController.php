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
        //Рендерем вьюшку Home
        return Inertia::render('Home');
    }
}
