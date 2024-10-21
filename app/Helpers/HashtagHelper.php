<?php

namespace App\Helpers;

class HashtagHelper
{
    /**
     * ハッシュタグのジャンルを手動でマッピングする
     * @param string $text ハッシュタグのテキスト
     * @return string ジャンルの名前
     */
    public static function manualMapping($text)
    {
        \Log::info('manualMapping called', ['text' => $text]);  // デバッグログ

        // 設定ファイルからジャンルと関連キーワードを読み込む
        $keywords = config('keywords');

        \Log::info('Config keywords loaded', ['keywords' => $keywords]);  // デバッグログ

        foreach ($keywords as $genre => $words) {
            foreach ($words as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    \Log::info('Match found', ['text' => $text, 'keyword' => $keyword, 'genre' => $genre]);  // デバッグログ
                    return $genre;
                }
            }
        }

        \Log::info('No match found', ['text' => $text]);  // デバッグログ

        return 'トレンド';
    }
}
