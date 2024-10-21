<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/like.css') }}">
    <link rel="stylesheet" href="{{ asset('css/messages.css') }}">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <!-- 戻るボタンを追加 -->
            @if (Route::currentRouteName() != 'posts.index')
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">☜</a>
            @endif
        </div>
    </header>

    <!-- コンテンツ -->
    @yield('content')

    <footer class="footer">
        <div class="container">
            <p>© 2024 My Website. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

    <script src="{{ asset('js/like.js') }}"></script>
    <script src="{{ asset('js/popstate.js') }}"></script>
    <script src="{{ asset('js/messages.js') }}"></script>
</body>
</html>
