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
        $trendingHashtags = Cache::remember('trending_hashtags', 60, function () {
            return Hashtag::where('count', '>', 0)
                ->orderBy('count', 'desc')
                ->take(20)
                ->get();
        });

        // 前回のトレンドデータをキャッシュから取得
        $previousTrendingHashtags = Cache::get('previous_trending_hashtags', []);

        // 各ハッシュタグの順位変動を設定
        foreach ($trendingHashtags as $index => $hashtag) {
            if (is_null($hashtag->genre)) {
                // カスタムマッピングを使用してジャンルを設定
                $hashtag->genre = HashtagHelper::manualMapping($hashtag->name);
                $hashtag->save();
                \Log::info('Genre set for hashtag', ['hashtag' => $hashtag->name, 'genre' => $hashtag->genre]);
            }

            // 前回のデータと比較して順位変動を判定
            $previousIndex = collect($previousTrendingHashtags)->search(function ($prevHashtag) use ($hashtag) {
                return $prevHashtag['name'] === $hashtag->name;
            });

            // ログに前回のインデックスを出力
            \Log::info('Previous index for hashtag', ['hashtag' => $hashtag->name, 'previous_index' => $previousIndex]);

            if ($previousIndex !== false) {
                // 前回の順位と現在の順位を比較して変動を計算
                if ($previousIndex > $index) {
                    $hashtag->trend_change = 'up';  // 順位が上昇
                } elseif ($previousIndex < $index) {
                    $hashtag->trend_change = 'down';  // 順位が下降
                } else {
                    $hashtag->trend_change = 'same';  // 順位変動なし
                }
            } else {
                // 新しくランクインした場合
                $hashtag->trend_change = 'new';
            }

            // 順位変動の情報をログ出力
            \Log::info('Trend change set for hashtag', ['hashtag' => $hashtag->name, 'trend_change' => $hashtag->trend_change]);
        }

        // 前回のトレンドデータをキャッシュに保存（ループの後で更新）
        Cache::put('previous_trending_hashtags', $trendingHashtags->toArray(), 60);

        // ビューにデータを渡す
        return view('posts.index', compact('trendingHashtags'));
    }
}
