<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Flashcard;
use App\Models\Language;
use App\Exports\FlashCardsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    // フラッシュカードの一覧を表示する
    public function index(User $user = null)
    { 
        // contentをEager Loadingで取得し、ユーザーに関連するフラッシュカードを取得
        if ($user) {
            // 特定のユーザーのフラッシュカードを取得
            $flashcards = $user->flashcards()->with(['pairs.frontContent', 'pairs.backContent'])->get();
        } else {
            // 全てのフラッシュカードを取得
            $flashcards = Flashcard::with(['pairs.frontContent', 'pairs.backContent'])->get();
        }
    
        return view('flashcards.index', compact('flashcards', 'user'));
    }

     /**
     * 指定されたフラッシュカードで練習するページを表示する
     */
    public function practice($id)
    {
        // 中間テーブルも取得する
                // id が１の
        // user情報を取得する
        // usersテーブルにある
        // IDが1のユーザー情報を取得
        // usersテーブルから取得するが、ここでは明示的にEager Loadingは不要
        $user = User::find(1);

        dd($user);

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

    public function exportFlashCardById($id, $type)
    {
        // Determine the appropriate format and file extension
        switch (strtolower($type)) {
            case 'csv':
                $format = ExcelFormat::CSV;
                $extension = 'csv';
                break;
            case 'html':
                $format = ExcelFormat::HTML;
                $extension = 'html';
                break;
            case 'xlsx':
                $format = ExcelFormat::XLSX;
                $extension = 'xlsx';
                break;
            case 'pdf':
                $format = ExcelFormat::TCPDF;
                $extension = 'pdf';
                break;        
            default:
                $format = ExcelFormat::CSV;
                $extension = 'csv';
                // TODO フォントセット（日本語）
                break;
        }

        // Export the file in the specified format
        return Excel::download(new FlashCardsExport($id), 'flash_card_' . $id . '.' . $extension, $format);
    }
}
