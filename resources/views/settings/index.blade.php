@extends('layouts.app_original')

@section('content')
<div class="settings-page">
    <h2>設定</h2>

    <!-- カスタマイズセクション -->
    <div class="settings-section">
        <h3>カスタマイズ</h3>

        <!-- テーマカラーの切り替え -->
        <div class="customize-option">
            <label for="theme-toggle">テーマカラー</label>
            <button id="theme-toggle" onclick="toggleDarkMode()">ダークモードに切り替え</button>
        </div>

        <!-- 言語設定 -->
        <div class="customize-option">
            <label for="language-select">言語設定</label>
            <select id="language-select" onchange="changeLanguage()">
                <option value="ja">日本語</option>
                <option value="en">English</option>
                <!-- 他の言語も追加可能 -->
            </select>
        </div>
    </div>
</div>
@endsection
