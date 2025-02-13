<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

//Для принятия хуков от бота
Route::post('/webhook', WebhookController::class);

/** HOMEPAGE */
Route::get('/', [HomeController::class, 'index'])->name('home');
