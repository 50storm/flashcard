<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashcard;
use App\Models\Content;
use App\Models\FlashcardContent;

// php artisan db:seed --class=FlashcardContentSeeder
class FlashcardContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のフラッシュカードとコンテンツの数を取得
        $flashcards = Flashcard::all();
        $contents = Content::all();

        // コンテンツを順番に使うためのインデックスを初期化
        $contentIndex = 0;

        // 各フラッシュカードに対して、5つのコンテンツを設定
        foreach ($flashcards as $flashcard) {
            for ($i = 0; $i < 5; $i++) {
                FlashcardContent::create([
                    'flashcard_id' => $flashcard->id,
                    'content_id' => $contents[$contentIndex]->id, // 順番にコンテンツIDを設定
                    'side_type' => $i, // 側のタイプ（0～4まで設定）
                ]);

                // コンテンツのインデックスを更新し、範囲外にならないようにする
                $contentIndex = ($contentIndex + 1) % $contents->count();
            }
        }
    }
}
