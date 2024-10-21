@extends('layouts.app_original')

@section('content')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-left">
                <div class="card-header">
                    「{{ $searchTerm }}」の検索結果:
                </div>
                @if($posts->isEmpty())
                    <div class="no-tweet text-center">
                        <h5>該当するツイートが見つかりませんでした。</h5>
                    </div>
                @else
                    @foreach($posts as $post)
                    <div class="card-body">
                        <div class="user-profile">
                            <a href="{{ route('users.show', ['id' => $post->retweet_id ? $post->originalPost->user->id : $post->user->id]) }}" class="poster">
                                @if($post->retweet_id)
                                    @if($post->originalPost->user->profile_image)
                                        <img src="{{ asset('storage/' . $post->originalPost->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                    @endif
                                    <div class="post-name">{{ $post->originalPost->user->name }}</div>
                                @else
                                    @if($post->user->profile_image)
                                        <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="プロフィール画像" class="profile-image">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image">
                                    @endif
                                    <div class="post-name">{{ $post->user->name }}</div>
                                @endif
                                <div class="posted-ago">
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                            </a>
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
                            {{-- <h5 class="card-title">タイトル : {{ $post->title }}</h5>
                            <p class="card-text">内容 : {{ $post->body }}</p> --}}

                            <!-- プロフィール画像とユーザー名 -->
                            {{-- <div class="user-profile">
                                <span>投稿者：
                                    @if($post->user->profile_image)
                                        <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                    @endif
                                    {{ $post->user->name }}
                                </span>
                            </div>
                        </a> --}}

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
                    {{-- <div class="card-footer text-muted">
                        {{ $post->created_at->diffForHumans() }}
                    </div> --}}
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('like.js') }}"></script>
@endsection
