<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function show($id)
    {
        $user = User::with('following', 'followers')->findOrFail($id); // リレーションを事前ロード
        return view('account.show', compact('user'));
    }

    public function edit()
    {
        // ログイン中のユーザー情報を取得して編集画面に表示
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $user = auth()->user();

        // 更新された項目を記録する配列
        $updatedFields = [];

        // 名前が更新された場合
        if ($request->input('name') !== $user->name) {
            $updatedFields[] = '名前';
            $user->name = $request->input('name');
        }

        // メールアドレスが更新された場合
        if ($request->input('email') !== $user->email) {
            $updatedFields[] = 'メールアドレス';
            $user->email = $request->input('email');
        }

        // プロフィール画像が更新された場合
        if ($request->hasFile('profile_image')) {
            $updatedFields[] = 'プロフィール画像';
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        // 更新された項目をビューに渡す
        return view('account.update-success', ['updatedFields' => $updatedFields]);
    }

}
