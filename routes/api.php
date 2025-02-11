<?php

use App\Http\Controllers\ContentController;
use Illuminate\Support\Facades\Route;

//Получение динамических vue компонентов
Route::get('/content/home-title', [ContentController::class, 'getHomeTitle']);
