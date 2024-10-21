@extends('layouts.app_original')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('posts.update', $post->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-group">
                    <label class="items">タイトル</label>
                    <input type="text" class="form-control" value="{{ $post->title }}" name="title">
                </div>
                <div class="form-group">
                    <label class="items">内容</label>
                    <textarea class="form-control" rows="5" name="body">{{ $post->body }}</textarea>
                </div>

                <div class="form-group">
                    <label for="media">メディアをアップロード</label>
                    <input type="file" name="media" class="form-control" accept="image/*,video/*">
                </div>

                <button type="submit" class="btn btn-primary custom-margin">更新する</button>
            </form>
        </div>
    </div>
</div>

@endsection
