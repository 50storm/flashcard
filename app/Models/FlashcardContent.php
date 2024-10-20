<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardContent extends Model
{
    use HasFactory;

     // テーブル名を指定
     protected $table = 'flashcard_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['flashcard_id', 'content_id', 'side_type'];

    /**
     * FlashcardContent belongs to a Flashcard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class);
    }

    /**
     * FlashcardContent belongs to a Content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
