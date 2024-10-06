<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// 上位クラスのUserでCanResetPasswordなどのトレイトを実装
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification; // カスタム通知クラスをインポート

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * パスワードリセット通知を送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
       // user情報を取得したい=>$thisで取れる
       $userName = $this->name;
       $userEmail = $this->email;
        $this->notify(new CustomResetPasswordNotification($token, $this));
    }

    protected static function boot()
    {
        parent::boot();

        // When a new record is being created
        static::creating(function ($model) {
            $model->created_by = 'system';  // Set the creator to 'system'
        });

        // When a record is being updated
        static::updating(function ($model) {
            $model->updated_by = 'system';  // Set the updater to 'system'
        });
    }
}
