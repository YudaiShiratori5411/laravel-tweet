<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Hashtag;
use Illuminate\Support\Facades\DB;
use App\Helpers\HashtagHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TrendController;
use Carbon\Carbon;

class PostController extends Controller
{
    protected $trendController;

    public function __construct(TrendController $trendController)
    {
        $this->trendController = $trendController;
    }

    public function index()
    {
        $posts = Post::withCount('retweets')->with('bookmarks')->orderBy('created_at', 'desc')->get();

        $user = auth()->user();

        // フォロワー数とフォロー数を取得
        $followerCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // トレンドデータを取得
        $trendingData = $this->trendController->index();

        // トレンドのハッシュタグを取得
        $trendingHashtags = $trendingData['trendingHashtags'];

        // $hashtag = Hashtag::where('name', '霧')->withCount('posts')->first();
        // dd($hashtag->count);

        // ビューに必要な変数を渡す
        return view('posts.index', compact('posts', 'user', 'followerCount', 'followingCount', 'trendingHashtags'));
    }

    // 投稿の作成ページを表示
    function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // バリデーションルールを定義
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
        ]);

        try {
            // トランザクションを開始
            DB::transaction(function() use ($validatedData, $request) {
                // 新しい投稿を作成
                $post = new Post();
                $post->title = $validatedData['title'];
                $post->body = $validatedData['body'];
                $post->user_id = Auth::id();

                // メディアファイルの保存
                if ($request->hasFile('media')) {
                    $filePath = $request->file('media')->store('public/media');
                    $post->media_path = $filePath;
                }

                // 投稿をデータベースに保存
                $post->save();

                // ログに投稿が保存されたことを記録
                \Log::info('Post saved', ['post_id' => $post->id]);

                // ハッシュタグを抽出
                preg_match_all('/#([\wぁ-んァ-ヶ一-龠々ー]+)/u', $post->body, $matches);
                $hashtags = $matches[1];

                // ログに抽出されたハッシュタグを記録
                \Log::info('Hashtags extracted', ['hashtags' => $hashtags]);

                // ハッシュタグのIDを取得して投稿と関連付け
                if (!empty($hashtags)) {
                    $hashtagIds = [];

                    foreach ($hashtags as $tagName) {
                        // ハッシュタグを取得または作成
                        $hashtag = Hashtag::firstOrCreate(['name' => $tagName]);
                        \Log::info('Hashtag created or found', ['hashtag_id' => $hashtag->id]);

                        // カウントをインクリメント
                        $hashtag->increment('count');

                        // ジャンルが未設定の場合、manualMappingでジャンルを設定
                        if (is_null($hashtag->genre)) {
                            $hashtag->genre = HashtagHelper::manualMapping($tagName);
                            $hashtag->save();
                            \Log::info('Genre mapped for hashtag', ['hashtag' => $tagName, 'genre' => $hashtag->genre]);
                        }

                        // ハッシュタグIDを収集
                        $hashtagIds[] = $hashtag->id;
                    }

                    // 投稿とハッシュタグを関連付け
                    $post->hashtags()->sync($hashtagIds);
                    \Log::info('Hashtags associated with post', ['post_id' => $post->id, 'hashtags' => $post->hashtags]);
                }
            });

            session()->flash('success', '投稿が正常に保存されました。');
        } catch (\Exception $e) {
            \Log::error('Post save failed', ['error' => $e->getMessage()]);
            return redirect()->route('posts.index')->withErrors('投稿の保存に失敗しました。');
        }

        return redirect()->route('posts.index');
    }


    // 投稿詳細ページを表示
    function show($id)
    {
        // 指定された投稿を取得し、リツイート数もカウント
        $post = Post::withCount('retweets')->findOrFail($id);

        return view('posts.show', compact('post'));
    }

    // 投稿の編集ページを表示
    function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', compact('post'));
    }

    // 投稿の更新処理
    function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return redirect()->route('posts.index')->with('success', '投稿を更新しました。');
    }

    public function destroy($id)
    {
        try {
            // 投稿を取得し、関連するハッシュタグを読み込む
            $post = Post::with('hashtags')->findOrFail($id);

            // 投稿に関連付けられたハッシュタグのIDを取得
            $hashtagIds = $post->hashtags()->pluck('hashtags.id');

            // 投稿を削除（bootメソッドでdetachは行われる）
            $post->delete();

            foreach ($hashtagIds as $hashtagId) {
                $hashtag = Hashtag::find($hashtagId);
                if ($hashtag) {
                    \Log::info('Current hashtag count before decrement', ['hashtag_id' => $hashtagId, 'count' => $hashtag->count]);

                    // 投稿削除に伴ってカウントを1減らす
                    $hashtag->decrement('count');
                    $hashtag->refresh(); // 最新のカウントを取得
                    \Log::info('After decrement', ['hashtag_id' => $hashtagId, 'new_count' => $hashtag->count]);

                    // ハッシュタグのカウントが0以下なら削除
                    if ($hashtag->count <= 0) {
                        $hashtag->delete();
                        \Log::info('Hashtag deleted', ['hashtag_id' => $hashtagId]);
                    }
                }
            }

            // 成功メッセージを表示
            session()->flash('success', '投稿が削除されました。');
        } catch (\Exception $e) {
            // エラーが発生した場合は、エラーメッセージを表示
            \Log::error('Failed to delete post', ['error' => $e->getMessage()]); // エラーをログに記録
            return redirect()->route('posts.index')->withErrors('投稿の削除に失敗しました。');
        }

        // 投稿一覧ページにリダイレクト
        return redirect()->route('posts.index');
    }

    public function toggleRetweet($id)
    {
        $user = Auth::user();
        $originalPost = Post::findOrFail($id);

        // ユーザーが既にリツイートしているか確認
        $existingRetweet = Post::where('retweet_id', $originalPost->id)
                                    ->where('user_id', $user->id)
                                    ->first();

        if ($existingRetweet) {
            // 既にリツイートしている場合はリツイートを取り消す
            $existingRetweet->delete();

            // リツイートカウントの更新
            $originalPost->retweets_count = Post::where('retweet_id', $originalPost->id)->count();
            $originalPost->save();

            return response()->json([
                'status' => 'unretweeted',
                'retweets_count' => $originalPost->retweets_count,
                'is_retweeted' => false
            ]);
        } else {
            // リツイートを作成
            $retweet = new Post();
            $retweet->title = $originalPost->title;
            $retweet->body = $originalPost->body;
            $retweet->user_id = $user->id;
            $retweet->retweet_id = $originalPost->id;
            $retweet->media_path = $originalPost->media_path;

            $retweet->save();

            // リツイートカウントの更新
            $originalPost->retweets_count = Post::where('retweet_id', $originalPost->id)->count();
            $originalPost->save();

            return response()->json([
                'status' => 'retweeted',
                'retweets_count' => $originalPost->retweets_count,
                'retweet' => $retweet,
                'user_name' => $user->name,
                'is_retweeted' => true
            ]);
        }
    }

    public function getTrendingHashtags()
    {
        $since = Carbon::now()->subHours(24);
        $posts = Post::where('created_at', '>=', $since)->get();

        $hashtags = [];

        foreach ($posts as $post) {
            $postHashtags = $this->extractHashtags($post->content);
            foreach ($postHashtags as $hashtag) {
                if (isset($hashtags[$hashtag])) {
                    $hashtags[$hashtag]++;
                } else {
                    $hashtags[$hashtag] = 1;
                }
            }
        }

        arsort($hashtags);

        return array_slice($hashtags, 0, 10, true);
    }

    private function extractHashtags($content)
    {
        preg_match_all('/#([\wぁ-んァ-ヶ一-龠々ー]+)/u', $content, $matches);
        return $matches[1];
    }

    // ハッシュタグワードをクリック → posts.hashtag
    public function searchByHashtag($name)
    {
        $hashtag = Hashtag::where('name', $name)->firstOrFail();
        $user = auth()->user();
        $posts = Post::where('body', 'LIKE', "%#{$hashtag->name}%")->get();

        return view('posts.hashtag', compact('posts', 'user', 'hashtag'));
    }
}
