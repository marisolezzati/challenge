<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\UsersController;


Route::get('/', [UsersController::class, 'index'])->name('welcome');
Route::resource('users', UsersController::class);
Route::resource('rates', RatesController::class);
