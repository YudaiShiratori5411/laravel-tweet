@extends('layouts.app_original')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-left">
                @if($bookmarks->isEmpty())
                    <div class="no-tweet text-center">
                        <h4>該当するツイートが見つかりませんでした。</h4>
                    </div>
                @else
                    @foreach($bookmarks as $bookmark)
                    {{-- <div class="card text-center"> --}}
                        <div class="card-body">
                            <div class="user-profile">
                                <a href="{{ route('users.show', ['id' => $bookmark->retweet_id ? $bookmark->originalPost->user->id : $bookmark->user->id]) }}" class="poster">
                                {{-- <a href="{{ route('posts.show', $bookmark->id) }}" class="text-decoration-none text-dark"> --}}
                                    @if($bookmark->retweet_id)
                                        @if($bookmark->user->profile_image)
                                            <img src="{{ asset('storage/' . $bookmark->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                        @else
                                            <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                        @endif
                                        <div class="post-name">{{ $bookmark->user->name }}</div>
                                    @else
                                        @if($bookmark->user->profile_image)
                                            <img src="{{ asset('storage/' . $bookmark->user->profile_image) }}" alt="プロフィール画像" class="profile-image">
                                        @else
                                            <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image">
                                        @endif
                                        <div class="post-name">{{ $bookmark->user->name }}</div>
                                    @endif
                                    <div class="posted-ago">
                                        {{ $bookmark->created_at->diffForHumans() }}
                                    </div>
                                </a>
                                <a href="{{ route('posts.show', $bookmark->id) }}" class="text-decoration-none text-dark">
                                    <div class="text-picture-section">
                                        <!-- テキスト部分 -->
                                        <p class="card-text">
                                            @if ($bookmark->retweet_id)
                                                {!! $bookmark->originalPost->contentWithHashtags() !!}
                                            @else
                                                {!! $bookmark->contentWithHashtags() !!}
                                            @endif
                                        </p>
                                    </div>
                                </a>

                                <a href="{{ route('posts.show', $bookmark->id) }}" class="text-decoration-none text-dark">
                                    <!-- 写真・動画 -->
                                    <div class="media text-center">
                                        @if($bookmark->retweet_id && $bookmark->originalPost->media_path)
                                            @include('partials.media', ['media_path' => $bookmark->originalPost->media_path])
                                        @elseif($bookmark->media_path)
                                            @include('partials.media', ['media_path' => $bookmark->media_path])
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <div class="like-retweet-bookmark-button">
                                <div class="like-section">
                                    <!-- いいねボタン -->
                                    <button id="like-button-{{ $bookmark->id }}"
                                        class="like-button {{ $bookmark->post->likes->contains('user_id', auth()->id()) ? 'liked' : '' }}"
                                        data-post-id="{{ $bookmark->id }}">
                                        ❤
                                    </button>
                                    <span id="likes-count-{{ $bookmark->id }}">{{ $bookmark->post->likes_count ?? 0 }}</span>
                                </div>
                                <!-- リツイートボタンとカウント -->
                                <div class="retweet-section">
                                    <button id="retweet-button-{{ $bookmark->id }}"
                                        class="retweet-button {{ $bookmark->retweets->contains('user_id', auth()->id()) ? 'retweeted' : '' }}"
                                        data-post-id="{{ $bookmark->id }}">
                                        <i class="fas fa-retweet" style="color: {{ $bookmark->retweets->contains('user_id', auth()->id()) ? 'green' : 'black' }};"></i>
                                    </button>
                                    <span id="retweet-count-{{ $bookmark->post->id }}">{{ $bookmark->post->retweets_count }}</span>
                                </div>

                                <!-- ブックマークボタンとカウント -->
                                <div class="bookmark-section">
                                    <button id="bookmark-button-{{ $bookmark->id }}"
                                            class="bookmark-button {{ $bookmark->post->bookmarks->contains('user_id', auth()->id()) ? 'bookmarked' : '' }}"
                                            data-post-id="{{ $bookmark->id }}">
                                        <i class="far fa-bookmark bookmark-icon"></i>
                                    </button>
                                    <span id="bookmark-count-{{ $bookmark->id }}">{{ $bookmark->post->bookmarks_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    <hr class="hr-index">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection