<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get('/', [ContactController::class, 'index'])->name('index');
Route::post('/store', [ContactController::class, 'store'])->name('store');
Route::get('/fetch-all', [ContactController::class, 'fetchAll'])->name('fetchAll');
Route::get('/edit', [ContactController::class, 'edit'])->name('edit');
Route::post('/update', [ContactController::class, 'update'])->name('update');
Route::get('/delete', [ContactController::class, 'delete'])->name('delete');
