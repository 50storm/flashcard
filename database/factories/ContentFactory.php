<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'language_id' => Language::where('language_code', 'en-US')->first()->id, // 例: 'en-US' を使用
        ];
    }
}
