@extends('layouts.app_original')

@section('content')
<div class="container mt-5">
    <h3>{{ $user->name }} のフォロワー</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <ul class="list-group">
        @foreach ($followers as $follower)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if($follower->profile_image)
                <img src="{{ asset('storage/' . $follower->profile_image) }}" alt="Profile Image" class="rounded-circle" width="50" height="50">
                @else
                <img src="{{ asset('storage/default-profile.png') }}" alt="Default Profile Image" class="rounded-circle" width="50" height="50">
                @endif
                <a href="{{ route('users.show', $follower->id) }}" class="ml-3">{{ $follower->name }}</a>
            </div>

            @if (Auth::user()->isFollowing($follower))
            <form action="{{ route('unfollow', $follower->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">フォロー解除</button>
            </form>
            @else
            <form action="{{ route('follow', $follower->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">フォローする</button>
            </form>
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endsection

