<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Model
{
    use HasFactory;

    protected $with = ['user'];
    protected $fillable = ['title', 'body', 'likes_count', 'bookmarks_count'];


    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function updateLikesCount()
    {
        $this->likes_count = $this->likes()->count();
        $this->save();
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class);
    }

    public function contentWithHashtags()
    {
        return preg_replace_callback(
            '/#([\wぁ-んァ-ヶ一-龠々ー]+)/u',
            function ($matches) {
                $hashtag = $matches[1];
                $url = route('posts.hashtag', ['hashtag' => $hashtag]);
                return '<a href="' . $url . '" class="hashtag-link">#' . $hashtag . '</a>';
            },
            $this->body
        );
    }

    public function retweets()
    {
        return $this->hasMany(Post::class, 'retweet_id');
    }


    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'retweet_id');
    }

    public function isRetweetedBy(User $user)
    {
        // すでにリレーションがロードされているかを確認
        if ($this->relationLoaded('retweets')) {
            return (bool) $this->retweets->where('user_id', $user->id)->count();
        }

        // リレーションがロードされていない場合は、クエリを実行
        return $this->retweets()->where('user_id', $user->id)->exists();
    }

    // リツイート数を更新するメソッド
    public function updateRetweetCount()
    {
        $this->retweet_count = $this->retweets()->count();
        $this->save();
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // ブックマーク数を更新するメソッド
    public function updateBookmarkCount()
    {
        $this->bookmarks_count = $this->bookmarks()->count();
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            // ここではハッシュタグを分離するのみで、デクリメントや削除はしない
            $hashtags = $post->hashtags;

            \Log::info('Hashtags associated with post', ['post_id' => $post->id, 'hashtags' => $hashtags]);

            if ($hashtags->isEmpty()) {
                \Log::warning('No hashtags found for post', ['post_id' => $post->id]);
                return;
            }

            // ハッシュタグの関連付けを解除
            $post->hashtags()->detach();

            \Log::info('Post deleting, hashtags detached', ['post_id' => $post->id]);
        });
    }
}
