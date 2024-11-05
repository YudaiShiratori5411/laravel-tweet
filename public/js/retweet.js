document.addEventListener('DOMContentLoaded', function() {
    // すべてのリツイートボタンに対してイベントリスナーを設定
    const retweetButtons = document.querySelectorAll('.retweet-button');

    retweetButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');

            // Ajaxリクエストを送信
            fetch(`/posts/${postId}/retweet`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'retweeted') {
                    // リツイートした場合の処理
                    this.classList.add('retweeted');
                    this.querySelector('i').style.color = 'green';
                } else if (data.status === 'unretweeted') {
                    // リツイートを取り消した場合の処理
                    this.classList.remove('retweeted');
                    this.querySelector('i').style.color = 'black';
                }

                // リツイートカウントの更新
                const retweetCountElement = document.getElementById(`retweet-count-${postId}`);
                retweetCountElement.textContent = data.retweets_count;
            })
            .catch(error => {
                console.error('エラー:', error);
            });

            // アニメーションを追加
            this.classList.remove('bouncing'); // 既にアニメーションが適用されていた場合はリセット
            this.classList.add('bouncing'); // クリック時にアニメーションを適用

            // 0.3秒後にアニメーションのクラスを削除して元に戻す
            setTimeout(() => {
                this.classList.remove('bouncing');
            }, 300);  // 0.3秒間アニメーションを維持
        });
    });
});



document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.retweet-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const icon = button.querySelector('.fa-retweet');

            if (button.classList.contains('retweeted')) {
                // リツイート解除
                button.classList.remove('retweeted');
                icon.classList.remove('retweet-green');
                icon.classList.add('retweet-gray');
            } else {
                // リツイート
                button.classList.add('retweeted');
                icon.classList.remove('retweet-gray');
                icon.classList.add('retweet-green');
            }
        });
    });
});


