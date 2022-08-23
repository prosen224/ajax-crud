<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;


Route::get('/', [EmployeeController::class, 'index'])->name('index');
Route::post('/', [EmployeeController::class, 'store'])->name('store');