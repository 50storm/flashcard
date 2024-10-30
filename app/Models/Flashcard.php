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
     * フラッシュカードと関連するコンテンツ（中間テーブル: flashcard_contents）のリレーションを定義
     * 
     * このメソッドは、FlashcardContentモデルとの1対多のリレーションを表します。
     * FlashcardContentテーブルには、flashcard_idによって関連付けられたコンテンツ情報が格納されています。
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flashcardContents()
    {
        return $this->hasMany(FlashcardContent::class, 'flashcard_id');
    }

    /**
     * FlashcardPairとのリレーション
     */
    public function pairs()
    {
        return $this->hasMany(FlashcardPair::class);
    }
}
