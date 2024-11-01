@section('styles')
    <link rel="stylesheet" href="{{ asset('css/customize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
@endsection

<div class="sidebar">
    <a href="{{ route('account.show', auth()->id()) }}" class="sidebar-image">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="top-image">
        @else
            <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="top-image">
        @endif
        <p class="user-name">{{ $user->name }}</p>
    </a>
    <div class="sidebar-header">
        <div class="follower-info">
            <span>フォロワー <strong>{{ $user->followers()->count() }}</strong></span>
        </div>
        <div class="following-info">
            <span>フォロー <strong>{{ $user->following()->count() }}</strong></span>
        </div>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('posts.create') }}">
                <i class="fas fa-plus"></i> <div>新規投稿する</div>
            </a>
        </li>
        <li>
            <a href="{{ route('account.show', auth()->id()) }}">
                <i class="fas fa-user"></i> <div>アカウント詳細</div>
            </a>
        </li>
        <li>
            <a href="{{ route('account.edit', auth()->id()) }}">
                <i class="fas fa-edit"></i> <div>アカウント編集</div>
            </a>
        </li>
        <li>
            <a href="{{ route('liked.index') }}">
                <i class="fas fa-heart"></i> <div>いいね</div>
            </a>
        </li>
        <li>
            <a href="{{ route('bookmarks.index') }}">
                <i class="fas fa-bookmark"></i> <div>ブックマーク</div>
            </a>
        </li>
        <li>
            <a href="{{ route('messages.index') }}">
                <i class="fas fa-envelope"></i> <div>ダイレクトメッセージ</div>
            </a>
        </li>
        <li>
            <a href="{{ route('settings.index') }}">
                <i class="icon fa fa-cog"></i> <div>設定</div>
            </a>
        </li>
        <li>
            @if(Auth::check())
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <div>ログアウト</div>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            @endif
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </ul>
</div>

@section('scripts')
    <script src="{{ asset('js/customize.js') }}"></script>
@endsection
