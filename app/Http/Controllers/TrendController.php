<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Hashtag;
use App\Helpers\HashtagHelper;
use Illuminate\Support\Facades\Cache;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\LanguageServiceClient;

class TrendController extends Controller
{
    public function index()
    {
        // キャッシュのキー
        $currentCacheKey = 'current_trending_hashtags';
        $previousCacheKey = 'previous_trending_hashtags';

        // 現在のトレンドデータをキャッシュから取得
        $trendingHashtags = Cache::remember($currentCacheKey, 60, function () {
            return Hashtag::where('count', '>', 0)
                ->orderBy('count', 'desc')
                ->take(20)
                ->get();
        });

        // 前回のトレンドデータをキャッシュから取得
        $previousTrendingHashtags = Cache::get($previousCacheKey, []);

        // 各ハッシュタグの順位変動を設定
        foreach ($trendingHashtags as $index => $hashtag) {
            if (is_null($hashtag->genre)) {
                $hashtag->genre = HashtagHelper::manualMapping($hashtag->name);
                $hashtag->save();
            }

            // 前回のデータと比較して順位変動を判定
            $previousIndex = collect($previousTrendingHashtags)->search(function ($prevHashtag) use ($hashtag) {
                return $prevHashtag['name'] === $hashtag->name;
            });

            if ($previousIndex !== false) {
                if ($previousIndex > $index) {
                    $hashtag->trend_change = 'up';
                } elseif ($previousIndex < $index) {
                    $hashtag->trend_change = 'down';
                } else {
                    $hashtag->trend_change = 'same';
                }
            } else {
                $hashtag->trend_change = 'new'; // 新しくランクインした場合
            }
        }

        // 今回のトレンドデータを次回の比較用に保存
        Cache::put($previousCacheKey, $trendingHashtags->toArray(), 60 * 24); // 24時間保持

        return view('posts.index', compact('trendingHashtags'));
    }
}
