<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'language' => $this->faker->randomElement([
                'English (United States)', 'English (United Kingdom)', 'English (Australia)', 
                'Japanese', 'Spanish (Spain)', 'French (France)', 'German (Germany)'
            ]), // 必要に応じて他の言語も追加
            'language_code' => $this->faker->randomElement([
                'en-US', 'en-GB', 'en-AU', 'ja', 'es-ES', 'fr-FR', 'de-DE'
            ]), // 対応する言語コードを設定
        ];
    }
}
