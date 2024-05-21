<?php

use App\Http\Controllers\AuthController;
use App\Http\Middlewares\BearerTokenMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware([BearerTokenMiddleware::class])->group(function () {
    Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
});
