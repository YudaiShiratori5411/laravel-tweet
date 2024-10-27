<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/trending.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/like-retweet-bookmark.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- サイドバー -->
            <div class="col-md-3 side-menu">
                    @include('partials.sidebar')
            </div>

            <div class="col-md-6">
                <div class="card text-left">
                    @if($posts->isEmpty())
                        <p>このハッシュタグに関連する投稿はありません。</p>
                    @else
                        @foreach($posts as $post)
                        <div class="card-body" data-post-url="{{ route('posts.show', $post->id) }}">
                            <!-- リツイートされた場合、リツイート情報を表示 -->
                            @if ($post->retweet_id)
                                <div class="retweeted-text">
                                    <i class="fas fa-retweet retweeted-icon"></i>
                                    <p class="retweet-info text-muted">{{ $post->user->name }} がリツイートしました</p>
                                </div>
                            @endif
                            <div class="user-profile">
                                <div class="image-name-date-profile">
                                    <a href="{{ route('users.show', ['id' => $post->retweet_id ? $post->originalPost->user->id : $post->user->id]) }}" class="poster">
                                        @if($post->retweet_id)
                                            @if($post->originalPost->user->profile_image)
                                                <img src="{{ asset('storage/' . $post->originalPost->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                            @else
                                                <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                            @endif
                                        @else
                                            @if($post->user->profile_image)
                                                <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="プロフィール画像" class="profile-image">
                                            @else
                                                <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image">
                                            @endif
                                        @endif
                                    </a>
                                    <a href="{{ route('posts.show', $post->id) }}" class="post-time text-decoration-none">
                                        @if($post->retweet_id)
                                            <div class="post-name">{{ $post->originalPost->user->name }}</div>
                                        @else
                                            <div class="post-name">{{ $post->user->name }}</div>
                                        @endif
                                        <div class="posted-ago">
                                            {{ $post->created_at->diffForHumans() }}
                                        </div>
                                    </a>
                                </div>

                                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                                    <div class="text-picture-section">
                                        <!-- テキスト部分 -->
                                        <p class="card-text">
                                            @if ($post->retweet_id)
                                                {!! $post->originalPost->contentWithHashtags() !!}
                                            @else
                                                {!! $post->contentWithHashtags() !!}
                                            @endif
                                        </p>
                                    </div>
                                </a>

                                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                                    <!-- 写真・動画 -->
                                    <div class="media text-center">
                                        @if($post->retweet_id && $post->originalPost->media_path)
                                            @include('partials.media', ['media_path' => $post->originalPost->media_path])
                                        @elseif($post->media_path)
                                            @include('partials.media', ['media_path' => $post->media_path])
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <div class="like-retweet-bookmark-button">
                                <div class="like-section">
                                    <!-- いいねボタン -->
                                    <button id="like-button-{{ $post->id }}"
                                        class="like-button {{ $post->likes->contains('user_id', auth()->id()) ? 'liked' : '' }}"
                                        data-post-id="{{ $post->id }}">
                                        ❤
                                    </button>
                                    <span id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 0 }}</span>
                                </div>

                                <!-- リツイートボタンとカウント -->
                                <div class="retweet-section">
                                    <button id="retweet-button-{{ $post->id }}"
                                        class="retweet-button {{ $post->retweets->contains('user_id', auth()->id()) ? 'retweeted' : '' }}"
                                        data-post-id="{{ $post->id }}">
                                        <i class="fas fa-retweet" style="color: {{ $post->retweets->contains('user_id', auth()->id()) ? 'green' : 'black' }};"></i>
                                    </button>
                                    <span id="retweet-count-{{ $post->id }}">{{ $post->retweets_count }}</span>
                                </div>

                                <!-- ブックマークボタンとカウント -->
                                <div class="bookmark-section">
                                    <button id="bookmark-button-{{ $post->id }}"
                                        class="bookmark-button {{ $post->bookmarks->contains('user_id', auth()->id()) ? 'bookmarked' : '' }}"
                                        data-post-id="{{ $post->id }}">
                                        <i class="far fa-bookmark bookmark-icon"></i>
                                    </button>
                                    <span id="bookmark-count-{{ $post->id }}">{{ $post->bookmarks_count ?? 0 }}</span>
                                </div>
                            </div>
                        <hr class="hr-index">
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            @if(!request()->is('hashtag/*'))
            <div class="col-md-3 searchform">
                <div class="searchbar">
                    <!-- 検索フォーム -->
                    <form action="{{ route('tweets.search') }}" method="GET" class="form-inline search-form">
                        <div class="search-box">
                            <input type="text" name="search" class="form-control search-input" placeholder="ツイートを検索">
                            <button type="submit" class="btn btn-search">
                                <span class="search-icon">🔍</span>
                            </button>
                        </div>
                    </form>
                </div>

                @if(isset($trendingHashtags) && !$trendingHashtags->isEmpty())
                    <div class="trending-hashtags">
                        <ul class="list-group">
                            @foreach ($trendingHashtags as $index => $hashtag)
                                <a href="{{ route('tweets.search', ['searchTerm' => $hashtag->name]) }}" class="hashtag-link">
                                    <li class="list-group-item">
                                        <div class="list-group-left">
                                            <strong>
                                                <span class="
                                                    @if ($index + 1 == 1) rank-1
                                                    @elseif ($index + 1 == 2) rank-2
                                                    @elseif ($index + 1 == 3) rank-3
                                                    @endif">
                                                    {{ $index + 1 }}.
                                                </span>
                                                    {{ $hashtag->name }}
                                            </strong>

                                            <div class="hashtag-details">
                                                <small>{{ $hashtag->count }} 件</small><br>
                                                <small>{{ $hashtag->genre ?? 'トレンド' }}</small>
                                            </div>
                                        </div>
                                        <div class="list-group-right">
                                            <div class="trend-arrows">
                                                <!-- 順位の変動を表示 -->
                                                <span class="trend-change">
                                                    @if($hashtag->trend_change == 'up')
                                                        <span class="arrow up">↑</span>
                                                    @elseif($hashtag->trend_change == 'down')
                                                        <span class="arrow down">↓</span>
                                                    @elseif($hashtag->trend_change == 'same')
                                                        <span class="arrow same">→</span>
                                                    @elseif($hashtag->trend_change == 'new')
                                                        <span class="arrow new">⭐</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p>トレンドはありません。</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/like.js') }}"></script>
    <script src="{{ asset('js/footer.js') }}"></script>
    <script src="{{ asset('js/retweet.js') }}"></script>
    <script src="{{ asset('js/hashtag-hover.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/customize.js') }}"></script>
    <script src="{{ asset('js/bookmark.js') }}"></script>
    <script src="{{ asset('js/posts.js') }}"></script>
</body>
</html>

























{{-- @extends('layouts.app_index')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/trending.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customize.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- サイドバー -->
        <div class="col-md-3">
            @include('partials.sidebar')
        </div>

        <!-- 投稿一覧 -->
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header">
                    投稿一覧
                </div>

                @if($posts->isEmpty())
                    <p>このハッシュタグに関連する投稿はありません。</p>
                @else
                    @foreach($posts as $post)
                    <div class="card-body">
                        <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                            <h5 class="card-title">
                                @if ($post->retweet_id)
                                    Retweet from: {{ $post->originalPost->user->name }} - {{ $post->originalPost->title }}
                                @else
                                    タイトル: {{ $post->title }}
                                @endif
                            </h5>

                            <p class="card-text">
                                @if ($post->retweet_id)
                                    {!! $post->originalPost->contentWithHashtags() !!}
                                @else
                                    {!! $post->contentWithHashtags() !!}
                                @endif
                            </p>

                            @if($post->retweet_id && $post->originalPost->media_path)
                                @include('partials.media', ['media_path' => $post->originalPost->media_path])
                            @elseif($post->media_path)
                                @include('partials.media', ['media_path' => $post->media_path])
                            @endif

                            <div class="user-profile">
                                <span>投稿者：
                                    <a href="{{ route('users.show', ['id' => $post->retweet_id ? $post->originalPost->user->id : $post->user->id]) }}" class="text-decoration-none">
                                        @if($post->retweet_id)
                                            @if($post->originalPost->user->profile_image)
                                                <img src="{{ asset('storage/' . $post->originalPost->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                            @else
                                                <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                            @endif
                                            {{ $post->originalPost->user->name }}
                                        @else
                                            @if($post->user->profile_image)
                                                <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                            @else
                                                <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                            @endif
                                            {{ $post->user->name }}
                                        @endif
                                    </a>
                                </span>
                            </div>
                        </a>

                        <div class="like-retweet-button">
                            <div class="like-section">
                                <!-- いいねボタン -->
                                <button id="like-button-{{ $post->id }}"
                                    class="like-button {{ $post->likes->contains('user_id', auth()->id()) ? 'liked' : '' }}"
                                    data-post-id="{{ $post->id }}">
                                    ❤
                                </button>
                                <span id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 0 }}</span>
                            </div>

                            <!-- リツイートボタンとカウント -->
                            <div class="retweet-section">
                                <button id="retweet-button-{{ $post->id }}"
                                    class="retweet-button {{ $post->retweets->contains('user_id', auth()->id()) ? 'retweeted' : '' }}"
                                    data-post-id="{{ $post->id }}">
                                    <!-- リツイートマーク -->
                                    <i class="fas fa-retweet" style="color: {{ $post->retweets->contains('user_id', auth()->id()) ? 'green' : 'black' }};"></i>
                                </button>
                                <!-- リツイート数の表示 -->
                                <span id="retweet-count-{{ $post->id }}">{{ $post->retweets_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        投稿日時 : {{ $post->created_at }}
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="searchbar">
                <!-- 検索フォーム -->
                <form action="{{ route('tweets.search') }}" method="GET" class="form-inline search-form">
                    <div class="search-box">
                        <input type="text" name="search" class="form-control search-input" placeholder="ツイートを検索">
                        <button type="submit" class="btn btn-search">
                            <span class="search-icon">🔍</span>
                        </button>
                    </div>
                </form>
            </div>

            @if(isset($trendingHashtags) && !$trendingHashtags->isEmpty())
            <div class="trending-hashtags">
                <ul class="list-group">
                    @foreach ($trendingHashtags as $index => $hashtag)
                    <li class="list-group-item">
                        <strong>
                            <span class="
                                @if ($index + 1 == 1) rank-1
                                @elseif ($index + 1 == 2) rank-2
                                @elseif ($index + 1 == 3) rank-3
                                @endif">
                                {{ $index + 1 }}.
                            </span>
                            {{ $hashtag->name }}
                        </strong>
                        <div class="hashtag-details">
                            <small>{{ $hashtag->count }} 件の投稿</small><br>
                            <small>{{ $hashtag->genre ?? 'トレンド' }}</small>
                            <!-- 順位の変動を表示 -->
                            <span class="trend-change">
                                @if($hashtag->trend_change == 'up')
                                    <span class="arrow up">↑</span>
                                @elseif($hashtag->trend_change == 'down')
                                    <span class="arrow down">↓</span>
                                @elseif($hashtag->trend_change == 'same')
                                    <span class="arrow same">→</span>
                                @elseif($hashtag->trend_change == 'new')
                                    <span class="arrow new">⭐</span>
                                @endif
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <p>トレンドはありません。</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/like.js') }}"></script>
    <script src="{{ asset('js/retweet.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/customize.js') }}"></script>
@endsection --}}
