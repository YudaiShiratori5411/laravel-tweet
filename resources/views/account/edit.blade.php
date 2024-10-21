@extends('layouts.app_original')

@section('content')
<div class="container">
    <h1>アカウント編集</h1>

    <form action="{{ route('account.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="profile_image">プロフィール画像</label>
            <input type="file" class="form-control" name="profile_image">
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
