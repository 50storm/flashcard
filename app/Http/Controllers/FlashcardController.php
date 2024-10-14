<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    // フラッシュカードを表示する
    public function index()
    {
        $flashcards = Flashcard::all();

        return view('flashcards.index', compact('flashcards'));
    }

    // フラッシュカードを追加するフォーム
    public function create()
    {
        return view('flashcards.create');
    }

    // フラッシュカードを保存する
    public function store(Request $request)
    {
        $request->validate([
            'english' => 'required',
            'japanese' => 'required',
        ]);

        Flashcard::create($request->all());

        return redirect()->route('flashcards.index')
                         ->with('success', 'Flashcard created successfully.');
    }

    // 編集フォームの表示
    public function edit($id)
    {
        $flashcard = Flashcard::findOrFail($id);

        return view('flashcards.edit', compact('flashcard'));
    }

    // フラッシュカードの更新
    public function update(Request $request, $id)
    {
        $request->validate([
            'english' => 'required',
            'japanese' => 'required',
        ]);

        $flashcard = Flashcard::findOrFail($id);
        $flashcard->update($request->all());

        return redirect()->route('flashcards.index')
                         ->with('success', 'Flashcard updated successfully.');
    }

    public function destroy($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        $flashcard->delete();

        return redirect()->route('flashcards.index')
                        ->with('success', 'Flashcard deleted successfully.');
    }
}
