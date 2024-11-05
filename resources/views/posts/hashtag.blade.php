@extends('layouts.app_original')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="hashtag-page">
                <div class="card-header">
                    <strong>「{{ $hashtag->name }}」の検索結果：</strong>
                </div>
                @foreach($posts as $post)
                    <div class="card card-hashtag text-left card-show">
                        <div class="card-body">
                            <div class="user-profile">
                                <!-- リツイートされた場合、リツイート情報を表示 -->
                                @if ($post->retweet_id)
                                    <div class="retweeted-text">
                                        <i class="fas fa-retweet retweeted-icon"></i>
                                        <p class="retweet-info text-muted">{{ $post->user->name }} がリツイートしました</p>
                                    </div>
                                @endif
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
                                        <!-- 投稿日時の表示位置 -->
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </a>

                                <!-- 投稿内容とハッシュタグ -->
                                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                                    <div class="text-picture-section-hashtag">
                                        <!-- テキスト部分 -->
                                        <p class="card-text-hashtag">
                                            @if ($post->retweet_id)
                                                {!! $post->originalPost->contentWithHashtags() !!}
                                            @else
                                                {!! $post->contentWithHashtags() !!}
                                            @endif
                                        </p>
                                    </div>
                                </a>

                                <!-- 写真・動画がある場合の表示 -->
                                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">
                                    <div class="media text-center">
                                        @if($post->retweet_id && $post->originalPost->media_path)
                                            @include('partials.media', ['media_path' => $post->originalPost->media_path])
                                        @elseif($post->media_path)
                                            @include('partials.media', ['media_path' => $post->media_path])
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <!-- いいね、リツイート、ブックマークボタン -->
                            <div class="like-retweet-bookmark-button">
                                <div class="like-section">
                                    <button id="like-button-{{ $post->id }}"
                                        class="like-button {{ $post->likes->contains('user_id', auth()->id()) ? 'liked' : '' }}"
                                        data-post-id="{{ $post->id }}">
                                        ❤
                                    </button>
                                    <span id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 0 }}</span>
                                </div>

                                <div class="retweet-section">
                                    <button id="retweet-button-{{ $post->id }}"
                                        class="retweet-button {{ $post->retweets->contains('user_id', auth()->id()) ? 'retweeted' : '' }}"
                                        data-post-id="{{ $post->id }}">
                                        <i class="fas fa-retweet {{ $post->retweets->contains('user_id', auth()->id()) ? 'retweet-green' : 'retweet-gray' }}"></i>
                                    </button>
                                    <span id="retweet-count-{{ $post->id }}">{{ $post->retweets_count }}</span>
                                </div>

                                <div class="bookmark-section">
                                    <button id="bookmark-button-{{ $post->id }}"
                                        class="bookmark-button {{ $post->bookmarks->contains('user_id', auth()->id()) ? 'bookmarked' : '' }}"
                                        data-post-id="{{ $post->id }}">
                                        <i class="far fa-bookmark bookmark-icon"></i>
                                    </button>
                                    <span id="bookmark-count-{{ $post->id }}">{{ $post->bookmarks_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 投稿日時の表示をfooterから削除 -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('like.js') }}"></script>
@endsection

