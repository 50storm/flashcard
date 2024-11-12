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
    public function storeFrontAndBackContents(Request $request, $flashcardId)
    {
        $validator = Validator::make($request->all(), [
            'contents' => 'required|array|min:2|max:2', 
            'contents.*.content' => 'required|string',
            'contents.*.language_code' => 'required|string|exists:languages,language_code',
            'contents.*.side_type' => 'required|integer|in:0,1',
        ], [
            'contents.required' => 'コンテンツは必須です。',
            'contents.min' => 'コンテンツはフロントとバックの2つ必要です。',
            'contents.max' => 'コンテンツはフロントとバックの2つに限定されています。',
            'contents.*.language_code.exists' => '指定された言語コードは存在しません。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $flashcard = Flashcard::findOrFail($flashcardId);
            $attachData = [];
            $addedContents = [];

            foreach ($request->input('contents') as $contentData) {
                $language = Language::where('language_code', $contentData['language_code'])->firstOrFail();
                $content = Content::create([
                    'content' => $contentData['content'],
                    'language_id' => $language->id,
                ]);
                $attachData[$content->id] = ['side_type' => $contentData['side_type']];
                // 新しく追加されたコンテンツ情報を格納
                $addedContents[] = [
                    'content' => $contentData['content'],
                    'language_code' => $contentData['language_code'],
                    'side_type' => $contentData['side_type'],
                ];
            }

            // 中間テーブルに一度に関連付け
            $flashcard->contents()->attach($attachData);    
            DB::commit();

            // 登録したデータを含めてJSONを返す
            return response()->json([
                'success' => true,
                'message' => 'contents have been added',
                'contents' => $addedContents,
            ], 201);    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error while adding contents: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'error' => 'コンテンツの登録中にエラーが発生しました。',
            ], 500);
        }
    }
}
