<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LikedPostController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

// 認証関連のルート
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Post関連のルート
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// Comment関連のルート
Route::get('/comments/create/{post_id}', [CommentController::class, 'create'])->name('comments.create');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Like関連のルート
Route::post('/posts/{post_id}/like', [LikeController::class, 'like'])->name('like');

// 認証確認のためのルート (JSONで返す)
Route::get('/check-auth', function () {
    return response()->json(['authenticated' => Auth::check()]);
});

// 他人のアカウント詳細ページ
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// アカウント関連のルート (認証が必要)
Route::middleware('auth')->group(function() {
    Route::get('/account/{id}', [AccountController::class, 'show'])->name('account.show');
    Route::get('/account/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/{id}/update', [AccountController::class, 'update'])->name('account.update');
});

// ツイート検索のルート
Route::get('/tweets/search', [TweetController::class, 'search'])->name('tweets.search');

// フォロー・フォロワー機能 (認証が必要)
Route::middleware('auth')->group(function() {
    Route::post('/users/{id}/follow', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/users/{id}/unfollow', [FollowController::class, 'unfollow'])->name('unfollow');
});
Route::get('/users/{id}/follows', [UserController::class, 'follows'])->name('user.follows');

// Route::get('/hashtag/{name}', [PostController::class, 'showByHashtag'])->name('posts.hashtag');

// リツイート・リツイート解除
Route::post('/posts/{id}/retweet', [PostController::class, 'toggleRetweet'])->name('posts.retweet');

Route::get('/trends', [TrendController::class, 'index']);

// Route::get('/posts', [TrendController::class, 'index'])->name('trends.index');

// ダイレクトメッセージ用のルート
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');

// ブックマークの追加
Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'store'])->middleware('auth');
// ブックマークの削除
Route::delete('/posts/{post}/bookmark', [BookmarkController::class, 'destroy'])->middleware('auth');

Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

// いいねした投稿一覧
Route::get('/liked', [LikedPostController::class, 'index'])->name('liked.index');

// ハッシュタグ検索のルート
Route::get('/hashtag/{hashtag}', [PostController::class, 'searchByHashtag'])->name('posts.hashtag');


Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
