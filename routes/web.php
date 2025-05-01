<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CharacterController;

Route::get('/', function () {
    return redirect()->route('movies.index');
});

Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

Route::resource('movies', MovieController::class);

Route::resource('characters', CharacterController::class)->except(['show']);

