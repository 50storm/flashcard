<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Http\Request;

class FlashcardApiController extends Controller
{
    // フラッシュカードの一覧のデータをJSONで返す
    public function getFlashcards()
    {
        $flashcards = Flashcard::all();
        return response()->json($flashcards);
    }

    // フラッシュカードを削除
    public function destroy($id)
    {
        $flashcard = Flashcard::find($id);

        if ($flashcard) {
            $flashcard->delete();
            return response()->json(['message' => '削除が成功しました。'], 200);
        } else {
            return response()->json(['message' => 'フラッシュカードが見つかりません。'], 404);
        }
    }

    // フラッシュカードを更新
    public function update(Request $request, $id)
    {
        $flashcard = Flashcard::find($id);

        if ($flashcard) {
            $flashcard->japanese = $request->input('japanese', $flashcard->japanese);
            $flashcard->english = $request->input('english', $flashcard->english);
            $flashcard->save();

            return response()->json(['message' => '更新が成功しました。'], 200);
        } else {
            return response()->json(['message' => 'フラッシュカードが見つかりません。'], 404);
        }
    }
}
