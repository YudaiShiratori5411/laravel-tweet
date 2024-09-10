@extends('layouts.app_original')
@section('content')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    投稿一覧
                </div>
                @foreach($posts as $post)
                <div class="card-body">
                    <!-- ポスト全体をクリックできるリンクにする -->
                    <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                        <h5 class="card-title">タイトル : {{ $post->title }}</h5>
                        <p class="card-text">内容 : {{ $post->body }}</p>
                        <p class="card-text">投稿者：{{ $post->user->name }}</p>
                    </a>

                    <!-- いいねボタン -->
                    <button id="like-button-{{ $post->id }}"
                        class="like-button {{ $post->likes->contains('user_id', auth()->id()) ? 'liked' : '' }}"
                        data-post-id="{{ $post->id }}">
                        ❤
                    </button>
                    <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                </div>
                <div class="card-footer text-muted">
                    投稿日時 : {{ $post->created_at }}
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-2">
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                新規投稿
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('like.js') }}"></script>
@endsection
