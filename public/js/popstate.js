// 現在のページがフォロー・フォロワーページやユーザー詳細ページの場合に履歴を追加
if (window.location.pathname.includes('/users/') || window.location.pathname.includes('/followers') || window.location.pathname.includes('/following')) {
    history.pushState(null, null, window.location.href);
}

window.addEventListener('popstate', function(event) {
    if (window.location.pathname.includes('/users/') || window.location.pathname.includes('/followers') || window.location.pathname.includes('/following')) {
        window.location.href = '/posts';
    }
});
