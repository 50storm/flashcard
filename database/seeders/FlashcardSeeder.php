<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashcard;

class FlashcardSeeder extends Seeder
{
    public function run()
    {
        // ビジネス英会話フレーズを挿入
        Flashcard::create(['english' => 'Thank you for your time.', 'japanese' => 'お時間をいただきありがとうございます。']);
        Flashcard::create(['english' => 'Could you please send me the report by tomorrow?', 'japanese' => '明日までにレポートを送っていただけますか？']);
        Flashcard::create(['english' => 'Let\'s schedule a meeting for next week.', 'japanese' => '来週、会議をスケジュールしましょう。']);
        Flashcard::create(['english' => 'I would like to discuss the new project.', 'japanese' => '新しいプロジェクトについて話し合いたいです。']);
        Flashcard::create(['english' => 'We are experiencing some technical difficulties.', 'japanese' => '技術的な問題が発生しています。']);
        Flashcard::create(['english' => 'Please let me know if you have any questions.', 'japanese' => '何か質問がありましたらお知らせください。']);
        Flashcard::create(['english' => 'I look forward to hearing from you.', 'japanese' => 'ご連絡をお待ちしております。']);
        Flashcard::create(['english' => 'Could we arrange a call?', 'japanese' => '電話のアレンジをしていただけますか？']);
        Flashcard::create(['english' => 'I will get back to you as soon as possible.', 'japanese' => 'できるだけ早くご連絡いたします。']);
        Flashcard::create(['english' => 'We need to review the budget.', 'japanese' => '予算を確認する必要があります。']);
        Flashcard::create(['english' => 'Please find the attached file.', 'japanese' => '添付ファイルをご確認ください。']);
        Flashcard::create(['english' => 'Could you please clarify that?', 'japanese' => 'それについて説明していただけますか？']);
        Flashcard::create(['english' => 'We are on track to meet the deadline.', 'japanese' => '締め切りに間に合うよう進めています。']);
        Flashcard::create(['english' => 'Let me know your availability.', 'japanese' => 'ご都合をお知らせください。']);
        Flashcard::create(['english' => 'Thank you for your cooperation.', 'japanese' => 'ご協力ありがとうございます。']);
        Flashcard::create(['english' => 'I apologize for the inconvenience.', 'japanese' => 'ご不便をおかけして申し訳ございません。']);
        Flashcard::create(['english' => "I haven't seen you in ages!.", 'japanese' => '長い間お会いしていませんでいsたね！']);
    }
}
