<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    // ブックマークされた投稿を表示
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()->with('post')->get();
        return view('bookmarks.index', compact('bookmarks'));
    }

    public function store(Request $request, Post $post)
    {
        $bookmark = Bookmark::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        // 投稿のブックマーク数を更新
        $post->updateBookmarkCount(); // これを追加

        return response()->json(['success' => true, 'bookmark' => $bookmark]);
    }

    public function destroy(Post $post)
    {
        $bookmark = Bookmark::where('user_id', Auth::id())->where('post_id', $post->id)->first();
        if ($bookmark) {
            $bookmark->delete();

            // 投稿のブックマーク数を更新
            $post->updateBookmarkCount(); // これを追加
        }

        return response()->json(['success' => true]);
    }
}
