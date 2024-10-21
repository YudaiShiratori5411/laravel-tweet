@extends('layouts.app_index')

@section('content')
<div class="container">
    <h1>トレンド</h1>
    <ul>
        @foreach($trendingHashtags as $hashtag => $count)
            <li>{{ '#' . $hashtag }} ({{ $count }}回使用されています)</li>
        @endforeach
    </ul>
</div>
@endsection
