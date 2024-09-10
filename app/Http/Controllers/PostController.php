<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    function index()
    {
        // Post.phpのポストデータを全取得 → $postsに格納
        $posts = Post::all();
        // dd($posts);  (デバッグ処理)
        // controllerで作成した変数をviewに反映させるには、compact()を使う
        return view('posts.index', compact('posts'));
    }

    function create()
    {
        return view('posts.create');
    }

    // Request = ユーザーが入力した全情報 → $requestに格納
    function store(Request $request)
    {
        // dd($request);
        // $requestに入っている値を、new Postでデータベースに保存するという記述
        $post = new Post;
        // 左辺：テーブル、右辺：formから送られてきた値
        $post -> title = $request -> title;
        $post -> body = $request -> body;
        $post -> user_id = Auth::id();

        $post -> save();

        // saveされたら、routeのposts.index(一覧表示)を呼び出す
        return redirect()->route('posts.index');
    }

    // $idはindex.blade.phpから送られてきたid
    function show($id)
    {
        $post = Post::find($id);

        return view('posts.show', ['post'=>$post]);
    }

    function edit($id)
    {
        $post = Post::find($id);

        return view('posts.edit', ['post'=>$post]);
    }

    function update(Request $request, $id)
    {
        $post = Post::find($id);

        $post -> title = $request -> title;
        $post -> body = $request -> body;
        $post -> save();

        return redirect()->route('posts.index')->with('success', '投稿を更新しました。');
    }

    function destroy($id)
    {
        $post = Post::find($id);
        $post -> delete();
        return redirect()->route('posts.index')->with('success', '投稿を削除しました。');
    }

}
