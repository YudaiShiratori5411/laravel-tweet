// ブックマークボタンのクリックイベント
document.querySelectorAll('.bookmark-button').forEach(button => {
    button.addEventListener('click', function () {
        const postId = this.dataset.postId;

        // クリックしたボタンの状態を確認
        const isBookmarked = this.classList.contains('bookmarked');
        const bookmarkCountElement = document.getElementById(`bookmark-count-${postId}`);
        let bookmarkCount = parseInt(bookmarkCountElement.innerText);

        // AJAXリクエストを送信
        fetch(`/posts/${postId}/bookmark`, {
            method: isBookmarked ? 'DELETE' : 'POST', // すでにブックマークされている場合は削除、それ以外は追加
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: postId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ボタンのスタイルを更新
                this.classList.toggle('bookmarked', !isBookmarked);

                // カウントを更新
                bookmarkCountElement.innerText = isBookmarked ? bookmarkCount - 1 : bookmarkCount + 1;
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// ブックマークアイコンのクリックアニメーション
document.querySelectorAll('.bookmark-icon').forEach(icon => {
    icon.addEventListener('click', function() {
        // 一時的に 'clicked' クラスを追加
        this.classList.add('clicked');

        // 200ms後にクラスを削除してリセット
        setTimeout(() => {
            this.classList.remove('clicked');
        }, 200);
    });
});
