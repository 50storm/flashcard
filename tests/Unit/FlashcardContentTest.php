<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Flashcard;
use App\Models\Content;
use App\Models\FlashcardContent;
use App\Models\Language; // 言語モデルをインポート
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlashcardContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用の言語データを挿入
        Language::factory()->create(['name' => 'English']);
        Language::factory()->create(['name' => 'Japanese']);
    }

    /** @test */
    public function it_can_retrieve_related_contents_through_flashcard()
    {
        // コンテンツを作成
        $frontContent = Content::factory()->create();
        $backContent = Content::factory()->create();

        // フラッシュカードを作成し、content_front_id と content_back_id に関連付け
        $flashcard = Flashcard::factory()->create([
            'content_front_id' => $frontContent->id,
            'content_back_id' => $backContent->id,
        ]);

        // フラッシュカードに関連するコンテンツを確認
        $this->assertEquals($frontContent->id, $flashcard->content_front_id);
        $this->assertEquals($backContent->id, $flashcard->content_back_id);
    }
}
