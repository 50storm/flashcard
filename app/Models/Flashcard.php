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
        return $this->belongsToMany(Content::class, 'flashcard_content', 'flashcard_id', 'content_id')
                    ->withPivot('side_type'); // 中間テーブルのフィールドも取得
    }

    /**
     * フラッシュカードに関連するタグを取得（多対多リレーション）
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'flashcard_tag', 'flashcard_id', 'tag_id');
    }

    /**
     * FlashcardPairとのリレーション
     */
    public function pairs()
    {
        return $this->hasMany(FlashcardPair::class);
    }
}
