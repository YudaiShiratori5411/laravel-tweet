<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'password', 'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 投稿とのリレーション
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    // コメントとのリレーション
    public function comments()
    {
        return $this->hasMany('App\Models\Comment'); // 修正：hasMany
    }

    // いいねとのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 管理者かどうかを確認
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // フォローしているユーザーとのリレーション
    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }


    // 特定のユーザーをフォローしているか確認
    public function isFollowing(User $user)
    {
        return $this->follows()->where('followed_id', $user->id)->exists();
    }

    // 特定のユーザーにフォローされているか確認
    public function isFollowedBy(User $user)
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    // フォロー処理
    public function follow(User $user)
    {
        if ($this->id !== $user->id && !$this->isFollowing($user)) {
            $this->follows()->attach($user->id, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    // フォロー解除処理
    public function unfollow(User $user)
    {
        if ($this->id !== $user->id && $this->isFollowing($user)) {
            $this->follows()->detach($user->id);
        }
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }
}
