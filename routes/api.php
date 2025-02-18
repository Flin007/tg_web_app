<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ContentController;
use Illuminate\Support\Facades\Route;

//Получение динамических vue компонентов
Route::get('/content/home-title', [ContentController::class, 'getHomeTitle']);

//Получение автомобилей
Route::get('/cars', [CarController::class, 'index']);

//Получение городов
Route::get('/cities', [CityController::class, 'index']);

//Получение брендов
Route::get('/brands', [BrandController::class, 'index']);
