<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class TweetController extends Controller
{
    public function search(Request $request)
    {
        // 検索バーからは 'search' パラメータ、トレンドからは 'searchTerm' を取得する
        $searchTerm = $request->input('search') ?? $request->input('searchTerm');

        // Postモデルを使って検索を行い、ユーザー情報も一緒に取得
        $posts = Post::with('user')
            ->where(function($query) use ($searchTerm) {
                // 投稿のタイトルまたは本文に検索キーワードが含まれるかを検索
                $query->Where('body', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orWhereHas('user', function($query) use ($searchTerm) {
                // ユーザー名に検索キーワードが含まれるかを検索
                $query->where('name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->paginate(30);

        return view('tweets.search_results', compact('posts', 'searchTerm'));
    }
}
