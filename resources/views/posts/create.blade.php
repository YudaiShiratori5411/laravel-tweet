@extends('layouts.app_original')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- ファイルアップロードを可能にするenctypeを追加 --}}
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" class="form-control" placeholder="タイトルを入力して下さい" name="title" required>
                </div>
                <div class="form-group">
                    <label>内容</label>
                    <textarea class="form-control" placeholder="内容" rows="5" name="body" required></textarea>
                </div>
                <div class="form-group">
                    <label for="media">メディアをアップロード</label>
                    <input type="file" name="media" class="form-control" accept="image/*,video/*">
                </div>
                <button type="submit" class="btn btn-primary">投稿する</button>
            </form>
        </div>
    </div>
</div>

@endsection
