<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($post_id)
    {
        $user_id = Auth::id();

        // Postモデルを取得
        $post = Post::findOrFail($post_id);

        // 既に「いいね」しているかを確認
        $existing_like = Like::where('post_id', $post_id)->where('user_id', $user_id)->first();

        if ($existing_like) {
            // 既に「いいね」している場合は「いいね」を解除
            $existing_like->delete();
            $liked = false;
        } else {
            // 「いいね」していない場合は新しく「いいね」を作成
            $like = new Like();
            $like->user_id = $user_id;
            $like->post_id = $post_id;
            $like->save();
            $liked = true;
        }

        // Postモデルのlikes_countを更新
        $post->updateLikesCount();

        // 最新の「いいね」の数を取得
        $likesCount = $post->likes_count;

        return response()->json([
            'liked' => $liked,
            'likesCount' => $likesCount
        ]);
    }
}

