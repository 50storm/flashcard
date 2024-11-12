<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Flashcard;
use App\Models\Content;
use App\Models\FlashcardPair;
use Illuminate\Support\Facades\DB;

class MigrateFlashcardContent extends Command
{
    protected $signature = 'migrate:flashcard-content';
    protected $description = 'Migrate flashcard_content data to flashcard_pairs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::transaction(function () {
            // すべてのフラッシュカードを取得
            $flashcards = Flashcard::with('contents')->get();

            foreach ($flashcards as $flashcard) {
                $frontContent = $flashcard->contents->where('pivot.side_type', 0)->first();
                $backContent = $flashcard->contents->where('pivot.side_type', 1)->first();

                if ($frontContent && $backContent) {
                    FlashcardPair::create([
                        'flashcard_id' => $flashcard->id,
                        'front_content_id' => $frontContent->id,
                        'back_content_id' => $backContent->id,
                    ]);
                }
            }
        });

        $this->info('Flashcard content migration completed successfully.');
    }
}
