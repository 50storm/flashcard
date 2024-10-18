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
    protected $fillable = ['language_id', 'content', 'type', 'published_at']; // 複数代入可能な属性

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime', // published_atをdatetime型にキャスト
        'type' => 'boolean', // typeをboolean型にキャスト (0 = front, 1 = back)
    ];

    /**
     * Get the language associated with the content.
     */
    public function language()
    {
        return $this->belongsTo(Language::class); // Language モデルとのリレーション
    }
}

