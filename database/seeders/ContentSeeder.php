<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

// php artisan db:seed --class=ContentSeeder
class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $englishSentences = [
            'Good morning', 'How are you?', 'Thank you', 'I love programming', 'It’s a sunny day',
            'Can I help you?', 'What time is it?', 'See you tomorrow', 'Have a nice day', 'Where is the station?',
            'I would like a coffee', 'What’s your name?', 'How much is this?', 'I am learning English', 'Can you repeat that?',
            'Please wait a moment', 'Excuse me', 'What do you do?', 'Where are you from?', 'Do you speak English?',
            'I don’t understand', 'Can you help me?', 'I’m sorry', 'I’m fine, thank you', 'How’s the weather?',
            'What’s your favorite food?', 'Let’s meet again', 'Do you like music?', 'I’ll call you later', 'Take care'
        ];

        $japaneseTranslations = [
            'おはようございます', 'お元気ですか？', 'ありがとう', 'プログラミングが大好きです', '今日は晴れです',
            'お手伝いしましょうか？', '今何時ですか？', 'また明日', '良い一日を', '駅はどこですか？',
            'コーヒーをお願いします', 'お名前は何ですか？', 'これはいくらですか？', '私は英語を学んでいます', 'もう一度言ってもらえますか？',
            '少々お待ちください', 'すみません', 'お仕事は何をしていますか？', 'どちらの出身ですか？', '英語を話しますか？',
            '分かりません', '助けてもらえますか？', 'ごめんなさい', '元気です、ありがとう', '天気はどうですか？',
            'あなたの好きな食べ物は何ですか？', 'また会いましょう', '音楽は好きですか？', '後で電話します', 'お元気で'
        ];

        // 英語と日本語の対応するフレーズをforeachで処理
        foreach ($englishSentences as $index => $englishSentence) {
            // 英語のデータを挿入
            Content::create([
                'language_id' => 1, // 英語データの場合
                'content' => $englishSentence,
            ]);

            // 対応する日本語のデータを挿入
            Content::create([
                'language_id' => 2, // 日本語データの場合
                'content' => $japaneseTranslations[$index],
            ]);
        }
    }
}
