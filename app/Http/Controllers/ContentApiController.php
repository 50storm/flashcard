<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Content;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContentApiController extends Controller
{
    /**
     * フロントとバックのコンテンツを一度に登録するメソッド
     *  ＜JSON形式＞
     *  {
     *       "contents": [
     *           {
     *               "content": "Hello World",
     *               "language_code": "en-US",
     *               "side_type": 0
     *           },
     *           {
     *               "content": "こんにちは",
     *               "language_code": "ja",
     *               "side_type": 1
     *           }
     *       ]
     *   }
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $flashcardId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFrontAndBackContents(Request $request, $flashcardId)
    {
        // バリデーションルールの定義
        $validator = Validator::make($request->all(), [
            'contents' => 'required|array|min:2|max:2', // フロントとバックの2つ
            'contents.*.content' => 'required|string',
            'contents.*.language_code' => 'required|string|exists:languages,language_code',
            'contents.*.side_type' => 'required|integer|in:0,1', // 0: Front, 1: Back
        ]);

        if ($validator->fails()) {
            // バリデーションエラーをJSON形式で返す
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // トランザクションの開始
            DB::beginTransaction();

            // フラッシュカードの取得
            $flashcard = Flashcard::findOrFail($flashcardId);

            foreach ($request->input('contents') as $contentData) {
                // 言語コードから言語IDを取得
                $language = Language::where('language_code', $contentData['language_code'])->firstOrFail();

                // コンテンツの作成
                $content = Content::create([
                    'content' => $contentData['content'],
                    'language_id' => $language->id,
                ]);

                // 中間テーブルへの関連付け
                $flashcard->contents()->attach($content->id, ['side_type' => $contentData['side_type']]);
            }

            // トランザクションのコミット
            DB::commit();

            // 成功レスポンスを返す
            return response()->json([
                'success' => true,
                'message' => 'contents have been added',
            ], 201);

        } catch (\Exception $e) {
            // トランザクションのロールバック
            DB::rollBack();

            // エラーレスポンスを返す
            return response()->json([
                'success' => false,
                'error' => 'コンテンツの登録中にエラーが発生しました。',
                'stackTrace' => env('APP_DEBUG') ? $e->getMessage() : null, // 本番環境では非表示
            ], 500);
        }
    }

    // 他のメソッド（updateFrontAndBackContents, updateFrontContents, updateBackContents）は省略
}
