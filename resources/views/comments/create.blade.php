@extends('layouts.app_original')

@section('content')

<div class="container container-posts-comment">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <h3>以下の記事にコメントします</h3>
            <div class="card mt-3">
                <div class="card-header">
                    <h5>タイトル：{{ $post->title }}</h5>
                </div>
                <div class="card-body">
                <p class="card-text">内容：{{ $post->body }}</p>
                <p>投稿日時：{{ $post->created_at }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <form action="{{ route('comments.store') }}" method="post">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div class="form-group">
                    <h5>コメント</h5>
                    <textarea class="form-control" name="body"
                    placeholder="内容" rows="5" name="body"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">コメントする</button>
            </form>
        </div>
    </div>
</div>
@endsection
