<?php

use App\Http\Controllers\testcontroller;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Для принятия хуков от бота
Route::post('/webhook', WebhookController::class);
Route::get('/test', testcontroller::class);

Route::get('/', function () {
    return Inertia::render('Home');
});
