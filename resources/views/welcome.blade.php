<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Welcome to Our App</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Hero Section -->
    <div class="hero flex flex-col justify-center items-center text-center">
        <div class="overlay"></div> <!-- 背景に半透明のオーバーレイを追加 -->
        <div class="content">
            <h1 class="text-5xl font-bold fade-in">Welcome to Our Awesome App</h1>
            <p class="text-xl mt-4 fade-in delay-200">Join the community and share your thoughts!</p>
            <button id="get-started-btn">Get Started</button>
            <script>
                document.getElementById('get-started-btn').addEventListener('click', function() {
                    // 認証されているかどうかを確認
                    fetch('/check-auth').then(response => response.json()).then(data => {
                        if (data.authenticated) {
                            window.location.href = '/posts'; // ログイン済みならツイート一覧へ
                        } else {
                            window.location.href = '/login'; // 未ログインならログインページへ
                        }
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>

