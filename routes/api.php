<?php

use App\Http\Controllers\Api\CarBrandController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarCityController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CarModelController;
use App\Http\Controllers\Api\TelegramController;
use Illuminate\Support\Facades\Route;

//Получение динамических vue компонентов
Route::get('/content/home-title', [ContentController::class, 'getHomeTitle']);

//Получение автомобилей
Route::get('/cars', [CarController::class, 'index']);

//Получение городов
Route::get('/cities', [CarCityController::class, 'index']);

//Получение брендов
Route::get('/brands', [CarBrandController::class, 'index']);

//Полученеи моделей авто
Route::get('/models', [CarModelController::class, 'index']);

// Группировка всех роутов, связанных с Telegram
Route::prefix('telegram')->group(function () {
    // Группировка роутов, связанных с пользователями в Telegram
    Route::prefix('user')->group(function () {
        Route::get('/checkStatus', [TelegramController::class, 'checkUserStatus']);
    });
});
