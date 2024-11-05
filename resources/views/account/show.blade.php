@extends('layouts.app_original')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container account-show">
    <!-- プロフィールヘッダー: 名前とフォローボタン -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ $user->name }} のプロフィール</h2>

        <!-- ログイン中のアカウントと詳細ページのアカウントが一致しない場合のみフォローボタンを表示 -->
        @if (auth()->id() !== $user->id)
            @if (auth()->user()->isFollowing($user))
                <!-- フォローを解除するボタン -->
                <form action="{{ route('unfollow', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">フォローを解除する</button>
                </form>
            @else
                <!-- フォローするボタン -->
                <form action="{{ route('follow', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">フォローする</button>
                </form>
            @endif
        @endif
    </div>

    <!-- Tabs for profile, followers, and following -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile">プロフィール</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="followers-tab" data-bs-toggle="tab" href="#followers">フォロワー</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="following-tab" data-bs-toggle="tab" href="#following">フォロー中</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Profile tab -->
        <div class="tab-pane fade show active" id="profile">
            <div class="profile-info">
                @if ($user->profile_image)
                    <!-- プロフィール画像が設定されている場合、その画像を表示 -->
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="profile-image">
                @else
                    <!-- プロフィール画像が未設定の場合、デフォルト画像を表示 -->
                    <img src="{{ asset('img/default-profile.png') }}" alt="デフォルト画像" class="profile-image">
                @endif
                <span class="ms-3">{{ $user->name }}</span>
                <p class="profile-email"><strong>メールアドレス:</strong> {{ $user->email }}</p>
            </div>
        </div>

        <!-- Followers tab -->
        <div class="tab-pane fade" id="followers">
            @if ($user->followers && count($user->followers) > 0)
                <ul class="list-group">
                    @foreach ($user->followers as $follower)
                        <li class="list-group-item">
                            <div class="profile-info">
                                <button class="profile-button" onclick="window.location.href='{{ route('users.show', $follower->id) }}'">
                                    @if ($follower->profile_image)
                                        <img src="{{ asset('storage/' . $follower->profile_image) }}" alt="プロフィール画像" class="profile-image">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" alt="デフォルト画像" class="profile-image">
                                    @endif
                                    <span class="ms-3">{{ $follower->name }}</span>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>フォロワーはいません。</p>
            @endif
        </div>

        <!-- Following tab -->
        <div class="tab-pane fade" id="following">
            @if ($user->following && count($user->following) > 0)
                <ul class="list-group">
                    @foreach ($user->following as $followed)
                        <li class="list-group-item">
                            <div class="profile-info">
                                <button class="profile-button" onclick="window.location.href='{{ route('users.show', $followed->id) }}'">
                                    @if ($followed->profile_image)
                                        <img src="{{ asset('storage/' . $followed->profile_image) }}" alt="プロフィール画像" class="profile-image">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" alt="デフォルト画像" class="profile-image">
                                    @endif
                                    <span class="ms-3">{{ $followed->name }}</span>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>フォロー中のユーザーはいません。</p>
            @endif
        </div>
    </div>
</div>

@endsection
