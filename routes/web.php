<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashcardController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('flashcards', FlashcardController::class);
