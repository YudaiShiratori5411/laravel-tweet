@extends('layouts.app_original')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="hashtag-page text-center">
                @foreach($posts as $post)
                    <div class="card text-center">
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
                                    @if($post->user->profile_image)
                                        <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                    @endif
                                    {{ $post->user->name }}
                                </div>
                            </a>

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
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
