<?php

use App\Http\Controllers\PlaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlaceController::class, 'home'])->name('home');
Route::get('/explore', [PlaceController::class, 'explore'])->name('explore');
Route::get('/places/{slug}', [PlaceController::class, 'show'])->name('places.show');
Route::get('/contribute', [PlaceController::class, 'contribute'])->name('contribute');
Route::get('/about', [PlaceController::class, 'about'])->name('about');
