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
                <i class="fas fa-plus"></i> 新規投稿する
            </a>
        </li>
        <li>
            <a href="{{ route('account.show', auth()->id()) }}">
                <i class="fas fa-user"></i> アカウント詳細
            </a>
        </li>
        <li>
            <a href="{{ route('account.edit', auth()->id()) }}">
                <i class="fas fa-edit"></i> アカウント編集
            </a>
        </li>
        <li>
            <a href="{{ route('liked.index') }}">
                <i class="fas fa-heart"></i> いいね
            </a>
        </li>
        <li>
            <a href="{{ route('bookmarks.index') }}">
                <i class="fas fa-bookmark"></i> ブックマーク
            </a>
        </li>
        <li>
            <a href="{{ route('messages.index') }}">
                <i class="fas fa-envelope"></i> ダイレクトメッセージ
            </a>
        </li>
        <li>
            <span class="menu-item" data-title="カスタマイズ" id="customize-item">
                <i class="icon fa fa-cog"></i> カスタマイズ
                <span class="mode-toggle-tag" id="mode-toggle-tag" style="display: none;">
                    ダークモードに切り替え
                </span>
            </span>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-sliders-h"></i> 設定
            </a>
        </li>
        <li>
            @if(Auth::check())
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> ログアウト
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
