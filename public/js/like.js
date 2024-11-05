document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const likeButton = document.getElementById(`like-button-${postId}`);
            const likesCount = document.getElementById(`likes-count-${postId}`);
            const heartIcon = likeButton.querySelector('.fa-heart');

            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.liked) {
                    likeButton.classList.add('liked');

                    // アニメーションを追加
                    heartIcon.classList.add('sparkle');
                    heartIcon.addEventListener('animationend', () => {
                        heartIcon.classList.remove('sparkle');
                    }, { once: true });

                } else {
                    likeButton.classList.remove('liked');
                }
                likesCount.textContent = data.likesCount;
            })
            .catch(error => console.error('Error:', error));
        });
    });
});


// いいねの状態に応じて色が変わるようにする
document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', () => {
        button.classList.toggle('liked'); // クリックで即時赤色に切り替え
        const postId = button.getAttribute('data-post-id');

        // いいね数の更新処理
        const likeCountElement = document.getElementById(`likes-count-${postId}`);
        let likeCount = parseInt(likeCountElement.innerText, 10);

        if (button.classList.contains('liked')) {
            // いいねされた場合、カウントを1増やす
            likeCount++;
        } else {
            // いいねが解除された場合、カウントを1減らす
            likeCount--;
        }

        likeCountElement.innerText = likeCount;

        // 必要に応じて、サーバーにリクエストを送信する処理を追加可能
        // fetch(`/like/${postId}`, { method: 'POST' });
    });
});



