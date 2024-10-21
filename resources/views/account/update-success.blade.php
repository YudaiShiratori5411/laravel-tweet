@extends('layouts.app_original')

@section('content')
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h4>アカウント情報が更新されました</h4>

        @if(!empty($updatedFields))
            <ul class='updated-items'>
                @foreach($updatedFields as $field)
                    <li>{{ $field }}</li>
                @endforeach
            </ul>
        @else
            <p>更新された項目はありません。</p>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('posts.index') }}" class="btn btn-primary">
            投稿一覧画面に戻る
        </a>
    </div>
</div>
@endsection
