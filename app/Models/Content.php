<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contents'; // 対応するテーブル名を指定

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'content']; // 複数代入可能な属性

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the language associated with the content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        // 外部キーが 'language_id'、対応する 'languages' テーブルの 'id' を参照
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function flashcards()
    {
        return $this->belongsToMany(Flashcard::class, 'flashcard_content', 'content_id', 'flashcard_id')
                    ->withPivot('side_type'); // 中間テーブルのフィールドも取得
    }
}
