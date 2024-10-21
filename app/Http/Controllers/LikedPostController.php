<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikedPostController extends Controller
{
    public function index()
    {
        // ログインしているユーザーがいいねした投稿を取得
        $likedPosts = Auth::user()->likedPosts()->with('user')->get();

        // いいねした投稿一覧を表示するビューに渡す
        return view('liked.index', compact('likedPosts'));
    }
}

