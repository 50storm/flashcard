<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoticeController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('flashcards', FlashcardController::class)->except(['destroy']);
    Route::get('flashcards/{id}/practice', [FlashcardController::class, 'practice'])->name('flashcards.practice');

    Route::resource('users.flashcards', FlashcardController::class)->except(['destroy']);

    Route::get('/flashcards/{id}/export/{type}', [FlashCardController::class, 'exportFlashCardById']);

});

require __DIR__.'/auth.php';
