@extends('layouts.app_original')

@section('content')
<div class="container mt-5">
    <h3>{{ $user->name }} がフォローしているユーザー</h3>

    <!-- エラーハンドリング -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- フォローしているユーザーがいるかチェック -->
    @if($follows->isEmpty())
        <p>{{ $user->name }} はまだ誰もフォローしていません。</p>
    @else
    <ul class="list-group">
        @foreach ($follows as $follow)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if($follow->profile_image)
                <img src="{{ asset('storage/' . $follow->profile_image) }}" alt="Profile Image" class="rounded-circle" width="50" height="50">
                @else
                <img src="{{ asset('storage/default-profile.png') }}" alt="Default Profile Image" class="rounded-circle" width="50" height="50">
                @endif
                <a href="{{ route('profile.show', $follow->id) }}" class="ms-3">{{ $follow->name }}</a> <!-- 修正点 -->
            </div>

            <!-- フォロー・フォロー解除ボタン -->
            @if (Auth::user()->isFollowing($follow))
            <form action="{{ route('unfollow', $follow->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">フォロー解除</button>
            </form>
            @else
            <form action="{{ route('follow', $follow->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">フォローする</button>
            </form>
            @endif
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection
