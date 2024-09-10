<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['post_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    # savedイベントを追加して、likes_countを更新
    protected static function booted()
    {
        static::saved(function ($like) {
            $like->post->updateLikesCount();
        });

        static::deleted(function ($like) {
            $like->post->updateLikesCount();
        });
    }

}
