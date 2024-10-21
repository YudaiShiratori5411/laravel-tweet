<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        // フォロー中のユーザーとフォロワーのユーザー情報を事前にロード
        $user = User::with([
            'following' => function ($query) {
                $query->select('users.id', 'users.name', 'users.profile_image');
            },
            'followers' => function ($query) {
                $query->select('users.id', 'users.name', 'users.profile_image');
            }
        ])->findOrFail($id);

        return view('account.show', compact('user'));
    }


    public function follow($id)
    {
        $userToFollow = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($currentUser->id === $userToFollow->id) {
            return redirect()->back()->with('error', '自分をフォローすることはできません。');
        }

        if (!$currentUser->isFollowing($userToFollow)) {
            $currentUser->follows()->attach($userToFollow->id);
            return redirect()->back()->with('success', 'フォローしました');
        }

        return redirect()->back()->with('error', 'すでにフォローしています');
    }

    public function unfollow($id)
    {
        $userToUnfollow = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($currentUser->isFollowing($userToUnfollow)) {
            $currentUser->follows()->detach($userToUnfollow->id);
            return redirect()->back()->with('success', 'フォローを解除しました');
        }

        return redirect()->back()->with('error', 'フォローしていません');
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers;

        return view('followers.index', compact('user', 'followers'));
    }

    public function follows($id)
    {
        $user = User::findOrFail($id);
        $follows = $user->follows;

        dd($follows); // ここでデバッグ

        return view('follows.index', compact('user', 'follows'));
    }
}
