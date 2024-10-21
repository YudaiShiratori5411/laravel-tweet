document.addEventListener('DOMContentLoaded', function() {
    const customizeItem = document.getElementById('customize-item');
    const modeToggleTag = document.getElementById('mode-toggle-tag');

    // ホバー時に「ダークモードに切り替え」を表示
    customizeItem.addEventListener('mouseenter', function() {
        modeToggleTag.style.display = 'inline';
    });

    // ホバー解除時に非表示
    customizeItem.addEventListener('mouseleave', function() {
        modeToggleTag.style.display = 'none';
    });

    // クリックでダークモードの切り替え
    customizeItem.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        modeToggleTag.textContent =
            document.body.classList.contains('dark-mode')
            ? 'ライトモードに切り替え'
            : 'ダークモードに切り替え';
    });
});
