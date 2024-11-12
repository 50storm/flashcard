<?php

namespace Database\Factories;

use App\Models\Flashcard;
use App\Models\Content;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlashcardFactory extends Factory
{
    protected $model = Flashcard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // ランダムなユーザーを作成
            'content_front_id' => Content::factory(), // ランダムな表のコンテンツを作成
            'content_back_id' => Content::factory(), // ランダムな裏のコンテンツを作成
        ];
    }
}
