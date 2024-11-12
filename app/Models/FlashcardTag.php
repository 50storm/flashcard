<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardTag extends Model
{
    use HasFactory;

    // テーブル名を明示的に指定する場合（Laravelの命名規則に従っていれば不要）
    protected $table = 'flashcard_tag';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['flashcard_id', 'tag_id'];

    /**
     * FlashcardTag belongs to a Flashcard.
     */
    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class);
    }

    /**
     * FlashcardTag belongs to a Tag.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
