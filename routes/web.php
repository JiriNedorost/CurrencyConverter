<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [ConversionController::class, 'index'])->name('index');
Route::post('/', [ConversionController::class, 'convert']);