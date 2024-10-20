<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    // 一括割り当て可能な属性
    protected $fillable = ['english', 'japanese'];

    /**
     * フラッシュカードに関連するコンテンツを取得
     */
    public function contents()
    {
        return $this->hasMany(FlashcardContent::class, 'flashcard_id');
    }

    /**
     * フラッシュカードに関連するタグを取得（多対多リレーション）
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'flashcard_tag', 'flashcard_id', 'tag_id');
    }
}
