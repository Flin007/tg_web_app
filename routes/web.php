<?php

use App\Http\Controllers\CarRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

//Для принятия хуков от бота
Route::post('/webhook', WebhookController::class);

//Создание заявки
Route::post('/request/create', [CarRequestController::class, 'create']);

/** HOMEPAGE */
Route::get('/', [HomeController::class, 'index'])->name('home');
