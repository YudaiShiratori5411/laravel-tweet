// 投稿をクリックしたときの遷移先
document.querySelectorAll('.card-body').forEach(function(cardBody) {
    cardBody.addEventListener('click', function(e) {
        // 以下の該当部分は遷移を防ぐ
        if (e.target.closest('.poster') ||
            e.target.closest('.like-button') ||
            e.target.closest('.retweet-button') ||
            e.target.closest('.bookmark-button') ||
            e.target.closest('.card-text a')) {
            return; // 何もしないで、元のリンクやボタンの動作をそのまま行う
        }

        // それ以外の部分がクリックされた場合は、投稿詳細ページに遷移
        const postUrl = cardBody.dataset.postUrl;
        window.location.href = postUrl;
    });
});
