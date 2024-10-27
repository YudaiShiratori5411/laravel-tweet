@extends('layouts.app_original')
@section('content')

<div class="container container-posts-fixed mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- ファイルアップロードを可能にするenctypeを追加 --}}
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <strong>タイトル</strong>
                    <input type="text" class="form-control" placeholder="タイトルを入力" name="title" required>
                </div>
                <div class="form-group">
                    <strong>内容</strong>
                    <textarea class="form-control" placeholder="内容" rows="5" name="body" required></textarea>
                </div>
                <div class="form-group">
                    <strong>
                        <label for="media">メディアをアップロード</label>
                    </strong>
                    <input type="file" name="media" class="form-control" accept="image/*,video/*">
                </div>
                <button type="submit" class="btn btn-primary">投稿する</button>
            </form>
        </div>
    </div>
</div>

@endsection
