<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'start_date',
        'end_date',
        'is_active',
    ];

    // 日付フィールドを自動的に Carbon インスタンスにキャスト
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
   ];
}
