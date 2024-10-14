<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\FlashcardApiController;

// API認証の例
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/flashcards/api', [FlashcardController::class, 'getFlashcards'])->name('flashcards.api');

Route::delete('/flashcards/{id}', [FlashcardApiController::class, 'destroy'])->name('flashcards.destroy');
