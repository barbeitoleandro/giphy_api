<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GifController;
use App\Http\Middlewares\BearerTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([BearerTokenMiddleware::class])->group(function () {
    Route::get('/gifs/search-by-name', [GifController::class, 'searchByName']);
    Route::get('/gifs/search-by-id', [GifController::class, 'searchById']);
    Route::post('/gifs/save', [GifController::class, 'save']);
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
