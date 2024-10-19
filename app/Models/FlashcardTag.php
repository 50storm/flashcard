<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardTag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['flashcard_id', 'tag_id'];

    /**
     * FlashcardTag belongs to a Flashcard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class);
    }

    /**
     * FlashcardTag belongs to a Tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
