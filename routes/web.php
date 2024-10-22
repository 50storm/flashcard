<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashcardController;

Route::get('/', function() {
    return view('welcome');
});



// Laravel Excel 
use App\Exports\UsersCsvExport;
use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Facades\Route;

Route::get('/export-users-csv', function () {
    return Excel::download(new UsersCsvExport, 'users.csv', \Maatwebsite\Excel\Excel::CSV);
});


// Route::post('/flashcards/{flashcard}/add-card', [FlashcardController::class, 'addCard'])->name('flashcards.addCard');

Route::get('flashcards/{id}/practice', [FlashcardController::class, 'practice'])->name('flashcards.practice');


// TODO DELETはのぞきたい
// Route::resource('flashcards', FlashcardController::class);
