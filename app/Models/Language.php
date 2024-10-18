<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language', 'language_code'];

    /**
     * Get the contents for the language.
     * 言語に関連する複数のコンテンツを取得するリレーション
     */
    public function contents()
    {
        return $this->hasMany(Content::class, 'language_id', 'id');
    }
}
