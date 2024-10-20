<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashcard;
use App\Models\Tag;
use App\Models\FlashcardTag;

// php artisan db:seed --class=FlashcardTagSeeder
class FlashcardTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のフラッシュカードとタグを取得
        $flashcards = Flashcard::all();
        $tags = Tag::all();

        // 各フラッシュカードに対して、ランダムに2つのタグを関連付け
        foreach ($flashcards as $flashcard) {
            // タグをランダムに2つ選択
            $randomTags = $tags->random(2);

            foreach ($randomTags as $tag) {
                FlashcardTag::create([
                    'flashcard_id' => $flashcard->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
    }
}
