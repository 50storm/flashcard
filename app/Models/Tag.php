<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag'];

    /**
     * タグとフラッシュカードのリレーション（多対多）
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function flashcards()
    {
        return $this->belongsToMany(Flashcard::class, 'flashcard_tag', 'tag_id', 'flashcard_id');
    }
}
