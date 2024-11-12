<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashcard;
use App\Models\Content;

// php artisan db:seed --class=FlashcardSeeder
class FlashcardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のコンテンツデータを取得
        $contents = Content::all();

        // 10件のフラッシュカードデータを作成
        for ($i = 0; $i < 10; $i++) {
            Flashcard::create([
                'user_id' => 1, // 仮のユーザーIDを使用 (適宜調整)
                'content_front_id' => $contents->random()->id, // ランダムな表のコンテンツID
                'content_back_id' => $contents->random()->id,  // ランダムな裏のコンテンツID
            ]);
        }
    }
}
