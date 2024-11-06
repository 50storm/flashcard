<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use App\Exports\FlashCardsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    // フラッシュカードを表示する
    public function index()
    {
        // contentをEager Loadingで取得
        $flashcards = Flashcard::with(['pairs.frontContent', 'pairs.backContent'])->get();
        return view('flashcards.index', compact('flashcards'));
    }

     /**
     * 指定されたフラッシュカードで練習するページを表示する
     */
    public function practice($id)
    {
        // 中間テーブルも取得する
        // Note: Joinよりもeager loadingが早いらしい。
        $flashcard = Flashcard::with(['pairs.frontContent', 'pairs.backContent'])
                          ->where('user_id', 1) // 条件として特定のユーザーID
                          ->where('id', $id) // 指定されたフラッシュカードのID
                          ->firstOrFail(); // 1つの結果を取得し、なければ404エラー
        $languages = Language::all();
        // 'practice'ビューにフラッシュカードのデータを渡す
        return view('flashcards.practice', compact('flashcard', 'languages'));
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

    public function exportFlashCardById($id)
    {
        return Excel::download(new FlashCardsExport($id), 'flash_card_' . $id . '.csv');
    }
}
