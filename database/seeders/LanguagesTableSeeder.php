<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['language' => 'English (United States)', 'language_code' => 'en-US'],
            ['language' => 'English (United Kingdom)', 'language_code' => 'en-GB'],
            ['language' => 'English (Australia)', 'language_code' => 'en-AU'],
            ['language' => 'English (Canada)', 'language_code' => 'en-CA'],
            ['language' => 'English (India)', 'language_code' => 'en-IN'],
            ['language' => 'English (New Zealand)', 'language_code' => 'en-NZ'],
            ['language' => 'English (South Africa)', 'language_code' => 'en-ZA'],
            ['language' => 'Japanese', 'language_code' => 'ja'],
            ['language' => 'Spanish (Spain)', 'language_code' => 'es-ES'],
            ['language' => 'Spanish (Mexico)', 'language_code' => 'es-MX'],
            ['language' => 'Spanish (Argentina)', 'language_code' => 'es-AR'],
            ['language' => 'Spanish (Colombia)', 'language_code' => 'es-CO'],
            ['language' => 'French (France)', 'language_code' => 'fr-FR'],
            ['language' => 'French (Canada)', 'language_code' => 'fr-CA'],
            ['language' => 'French (Switzerland)', 'language_code' => 'fr-CH'],
            ['language' => 'German (Germany)', 'language_code' => 'de-DE'],
            ['language' => 'German (Austria)', 'language_code' => 'de-AT'],
            ['language' => 'German (Switzerland)', 'language_code' => 'de-CH'],
            ['language' => 'Chinese (Simplified, China)', 'language_code' => 'zh-CN'],
            ['language' => 'Chinese (Traditional, Taiwan)', 'language_code' => 'zh-TW'],
            ['language' => 'Chinese (Hong Kong)', 'language_code' => 'zh-HK'],
            ['language' => 'Chinese (Singapore)', 'language_code' => 'zh-SG'],
            ['language' => 'Korean', 'language_code' => 'ko'],
            ['language' => 'Portuguese (Portugal)', 'language_code' => 'pt-PT'],
            ['language' => 'Portuguese (Brazil)', 'language_code' => 'pt-BR'],
            ['language' => 'Russian', 'language_code' => 'ru'],
            ['language' => 'Italian (Italy)', 'language_code' => 'it-IT'],
            ['language' => 'Italian (Switzerland)', 'language_code' => 'it-CH'],
            ['language' => 'Arabic (Saudi Arabia)', 'language_code' => 'ar-SA'],
            ['language' => 'Arabic (Egypt)', 'language_code' => 'ar-EG'],
            ['language' => 'Arabic (Morocco)', 'language_code' => 'ar-MA'],
            ['language' => 'Dutch (Netherlands)', 'language_code' => 'nl-NL'],
            ['language' => 'Dutch (Belgium)', 'language_code' => 'nl-BE'],
            ['language' => 'Hindi', 'language_code' => 'hi'],
            ['language' => 'Swedish (Sweden)', 'language_code' => 'sv-SE'],
            ['language' => 'Norwegian (Bokmål)', 'language_code' => 'nb-NO'],
            ['language' => 'Norwegian (Nynorsk)', 'language_code' => 'nn-NO'],
            ['language' => 'Danish', 'language_code' => 'da-DK'],
            ['language' => 'Finnish', 'language_code' => 'fi-FI'],
            ['language' => 'Polish', 'language_code' => 'pl'],
            ['language' => 'Greek', 'language_code' => 'el'],
            ['language' => 'Turkish', 'language_code' => 'tr'],
            ['language' => 'Thai', 'language_code' => 'th'],
            ['language' => 'Czech', 'language_code' => 'cs'],
            ['language' => 'Hungarian', 'language_code' => 'hu'],
        ];

        // Insert languages into the table
        DB::table('languages')->insert($languages);
    }
}
