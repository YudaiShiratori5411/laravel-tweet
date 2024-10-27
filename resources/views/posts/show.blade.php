@extends('layouts.app_original')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-show text-left">
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

                        <div class="retweet-section">
                            <button id="retweet-button-{{ $post->id }}"
                                class="retweet-button {{ $post->retweets->contains('user_id', auth()->id()) ? 'retweeted' : '' }}"
                                data-post-id="{{ $post->id }}">
                                <i class="fas fa-retweet" style="color: {{ $post->retweets->contains('user_id', auth()->id()) ? 'green' : 'black' }};"></i>
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
                    <hr class="hr-index">

                    <!-- オプションボタン -->
                    <div class="post-options">
                        <button class="options-button">・・・</button>
                        <div class="options-list">
                            @if (Auth::id() === $post->user_id)
                                <button onclick="location.href='{{ route('posts.edit', $post->id) }}'">編集する</button>
                                <form id="delete-form" action="{{ route('posts.destroy', $post->id) }}" method="post" style="margin-right: 1cm; margin: 0;">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-danger" id="delete-button" style="margin-right: 0.2cm;">削除する</button>
                                </form>
                            @endif
                            <button onclick="window.location='{{ route('comments.create', $post->id) }}'">コメントする</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                {{-- <div class="d-flex">
                    @if (Auth::id() === $post->user_id)
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary" style="margin-right: 0.2cm;">編集する</a>

                        <form action='{{ route('posts.destroy', $post->id) }}' method='post' style="margin-right: 1cm; margin: 0;">
                            @csrf
                            @method('delete')
                            <input type='submit' value='削除する' class="btn btn-danger" style="margin-right: 0.2cm;" onclick='return confirm("本当に削除しますか？");'>
                        </form>
                    @endif

                    <button type="button" class="btn btn-primary" onclick="window.location='{{ route('comments.create', $post->id) }}'">コメントする</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- コメント欄 -->
    <div class="row justify-content-center">
        <div class="comment mt-5">
            コメント一覧
            @foreach($post->comments as $comment)
                <div class="card mt-3">
                    <h5 class="card-header">投稿者：{{ $comment->user->name }}</h5>
                    <div class="card-body">
                        <h5 class="card-title">投稿日時：{{ $comment->created_at }}</h5>
                        <p class="card-text">内容：{{ $comment->body }}</p>

                        @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                            <!-- 削除ボタン -->
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('like.js') }}"></script>
<script src="{{ asset('js/post-detail.js') }}"></script>
@endsection
