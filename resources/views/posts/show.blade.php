@extends('layouts.app_original')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">
                    <h5>タイトル：{{ $post->title }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">内容：{{ $post->body }}</p>
                    <p>投稿日時：{{ $post->created_at }}</p>

                    <div class="d-flex">
                        @if (Auth::id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary" style="margin-right: 0.2cm;">編集する</a>

                            <form action='{{ route('posts.destroy', $post->id) }}' method='post' style="margin-right: 1cm; margin: 0;">
                                @csrf
                                @method('delete')
                                <input type='submit' value='削除する' class="btn btn-danger" style="margin-right: 0.2cm;" onclick='return confirm("本当に削除しますか？");'>
                            </form>
                        @endif

                        <button type="button" class="btn btn-primary" onclick="window.location='{{ route('comments.create', $post->id) }}'">コメントする</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            コメント一覧
            @foreach($post->comments as $comment)
                <div class="card mt-3">
                    <h5 class="card-header">投稿者：{{ $comment->user->name }}</h5>
                    <div class="card-body">
                        <h5 class="card-title">投稿日時：{{ $comment->created_at }}</h5>
                        <p class="card-text">内容：{{ $comment->body }}</p>

                        @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                            <!-- 削除ボタン -->
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('like.js') }}"></script>
@endsection
