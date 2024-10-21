<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Retweet;


class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id'];

    // ブックマークがどの投稿に関連しているかのリレーション
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // ブックマークがどのユーザーに関連しているかのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function retweets()
    {
        return $this->post->retweets();
    }

    public function bookmarks()
    {
        return $this->belongsTo(User::class);
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
            $this->post->body
        );
    }

}
