<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\NoticeController;


Route::get('/', function() {
    return view('welcome');
});


Route::get('/notices', [NoticeController::class, 'index']);

// Laravel Excel 
use App\Exports\UsersCsvExport;
use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Facades\Route;

Route::get('/export-users-csv', function () {
    return Excel::download(new UsersCsvExport, 'users.csv', \Maatwebsite\Excel\Excel::CSV);
});


// Route::post('/flashcards/{flashcard}/add-card', [FlashcardController::class, 'addCard'])->name('flashcards.addCard');

Route::get('flashcards/{id}/practice', [FlashcardController::class, 'practice'])->name('flashcards.practice');


// Flashcardリソースのルートからdestroyを除外
Route::resource('flashcards', FlashcardController::class)->except(['destroy']);

// ユーザーごとのFlashcardリソースのルート
// ルートモデルバインディング： Laravelは自動的に User モデルを解決してくれる
// ネストされたリソースルート：リソースルートをネストすることで、親リソース（この場合は users）に関連付けられた子リソース（flashcards）を扱いやすくする
Route::resource('users.flashcards', FlashcardController::class)->except(['destroy']);
// GET|HEAD        users/{user}/flashcards .............................................................. users.flashcards.index › FlashcardController@index
// POST            users/{user}/flashcards .............................................................. users.flashcards.store › FlashcardController@store
// GET|HEAD        users/{user}/flashcards/create ..................................................... users.flashcards.create › FlashcardController@create
// GET|HEAD        users/{user}/flashcards/{flashcard} .................................................... users.flashcards.show › FlashcardController@show
// PUT|PATCH       users/{user}/flashcards/{flashcard} ................................................ users.flashcards.update › FlashcardController@update
// GET|HEAD        users/{user}/flashcards/{flashcard}/edit ............................................... users.flashcards.edit › FlashcardController@edit


Route::get('/flashcards/{id}/export/{type}', [FlashCardController::class, 'exportFlashCardById']);
