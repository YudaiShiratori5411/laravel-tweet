<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;



class CommentController extends Controller
{
    public function create($id)
    {
        $post = Post::find($id);
        return view('comments.create', ['post'=>$post]);
    }

    public function store(Request $request)
    {
        $post = Post::find($request->post_id);
        $comment = new Comment;
        $comment -> body = $request -> body;
        $comment -> user_id = Auth::id();
        $comment -> post_id = $request -> post_id;
        $comment -> save();
        return redirect()->route('posts.index')->with('success', 'コメントを投稿しました。');
    }

    function destroy(Comment $comment)
    {
        // コメントの投稿者または管理者かどうかを確認
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'コメントの削除が許可されていません。');
        }

        // コメントを削除
        $comment->delete();

        // 投稿一覧にリダイレクト
        return redirect()->route('posts.index')->with('success', 'コメントを削除しました。');
    }

}
