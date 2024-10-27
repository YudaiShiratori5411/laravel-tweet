@extends('layouts.app_dm')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/messages.css') }}">
@endsection

@section('content')
<div class="dm">
    <!-- サイドバー -->
    <div class="sidebar">
        <div class="tab-switch">
            <div class="icon" id="send-message-icon">📩</div>
            <div class="label">メッセージを送る</div>
        </div>
        <div class="tab-switch">
            <div class="icon" id="sent-messages-icon">📤</div>
            <div class="label">送信一覧</div>
        </div>
        <div class="tab-switch">
            <div class="icon" id="received-messages-icon">📥</div>
            <div class="label">受信一覧</div>
        </div>
    </div>

    <div class="container container-message mt-4">
        <div class="sent-received-messages">
            <div class="default-content">
                <!-- 受信一覧（デフォルト表示） -->
                <div id="received-messages" class="tab-pane fade show active">
                    <h3>受信メッセージ</h3>
                    <ul class="list-group">
                        @foreach ($receivedMessages as $message)
                            <li class="list-group-item">
                                @if($message->sender->profile_image)
                                    <img src="{{ asset('storage/' . $message->sender->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                                @else
                                    <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                                @endif
                                <strong>{{ $message->sender->name }}：</strong>
                                {{ $message->message }}
                                <small class="text-muted">({{ $message->created_at->format('Y-m-d H:i') }})</small>
                            </li>
                        @endforeach
                    </ul>
                    <!-- ページネーションのリンク -->
                    {{ $receivedMessages->links() }}
                </div>
            </div>

            <!-- 送信一覧 -->
            <div id="sent-messages" class="tab-pane fade">
                <h3>送信メッセージ</h3>
                <ul class="list-group">
                    @foreach ($sentMessages as $message)
                        <li class="list-group-item">
                            @if($message->receiver->profile_image)
                                <img src="{{ asset('storage/' . $message->receiver->profile_image) }}" alt="プロフィール画像" class="profile-image" width="25" height="25">
                            @else
                                <img src="{{ asset('img/default-profile.png') }}" alt="デフォルトプロフィール画像" class="profile-image" width="25" height="25">
                            @endif
                            <strong>{{ $message->receiver->name }}：</strong>
                            {{ $message->message }}
                            <small class="text-muted">({{ $message->created_at->format('Y-m-d H:i') }})</small>
                        </li>
                    @endforeach
                </ul>
                <!-- ページネーションのリンク -->
                {{ $sentMessages->links() }}
            </div>
        </div>

        <!-- ダイレクトメッセージを送る -->
        <div id="send-message" class="tab-pane fade received-messages-tab">
            <div class="card card-messages p-3">
                <form action="{{ route('messages.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="receiver_id">送信先</label>
                        <select name="receiver_id" class="form-control">
                            @foreach (App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">メッセージ</label>
                        <textarea name="message" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="btn btn-primary">送信</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/messages.js') }}"></script>
@endsection
