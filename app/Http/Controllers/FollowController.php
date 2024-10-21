<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow($id)
    {
        $user = User::findOrFail($id);
        Auth::user()->follow($user);

        return redirect()->route('users.show', $id)->with('success', 'フォローしました！');
    }

    public function unfollow($id)
    {
        $user = User::findOrFail($id);
        Auth::user()->unfollow($user);

        return redirect()->route('users.show', $id)->with('success', 'フォローを解除しました！');
    }
}
