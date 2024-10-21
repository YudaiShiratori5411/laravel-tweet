@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $user->name }} さんのプロフィール</h3>
                    <!-- プロフィール画像の表示 -->
                    @if($user->profile_image)
                        <img src="{{ asset('storage/profile_images/'.$user->profile_image) }}" alt="プロフィール画像" class="img-thumbnail" style="width: 150px; height: 150px;">
                    @else
                        <img src="{{ asset('default_profile.png') }}" alt="デフォルト画像" class="img-thumbnail" style="width: 150px; height: 150px;">
                    @endif
                </div>
                <div class="card-body">
                    <p><strong>名前:</strong> {{ $user->name }}</p>
                    <p><strong>メールアドレス:</strong> {{ $user->email }}</p>
                    <p><strong>フォロワー数:</strong> {{ $user->followers()->count() }}</p>
                    <p><strong>フォロー中:</strong> {{ $user->follows()->count() }}</p>

                    <!-- 自分自身のアカウントページにはフォローボタンを表示しない -->
                    @if (Auth::id() !== $user->id)
                        @if (Auth::user()->isFollowing($user))
                            <form action="{{ route('unfollow', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">このユーザーのフォローを解除する</button>
                            </form>
                        @else
                            <form action="{{ route('follow', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">このユーザーをフォローする</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
