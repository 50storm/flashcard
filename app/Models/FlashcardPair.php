<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardPair extends Model
{
    protected $fillable = [
        'flashcard_id',
        'front_content_id',
        'back_content_id',
    ];

    /**
     * Flashcardとのリレーション
     */
    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class);
    }

    /**
     * フロントコンテンツとのリレーション
     */
    public function frontContent()
    {
        return $this->belongsTo(Content::class, 'front_content_id');
    }

    /**
     * バックコンテンツとのリレーション
     */
    public function backContent()
    {
        return $this->belongsTo(Content::class, 'back_content_id');
    }
}
