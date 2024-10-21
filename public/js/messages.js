document.addEventListener("DOMContentLoaded", function () {
    const tabContent = document.querySelectorAll('.tab-pane');
    const sidebarItems = document.querySelectorAll('.tab-switch');

    sidebarItems.forEach(item => {
        item.addEventListener('click', function () {
            const targetId = item.querySelector('.label').textContent === 'メッセージを送る' ? 'send-message' :
                             item.querySelector('.label').textContent === '送信一覧' ? 'sent-messages' : 'received-messages';

            tabContent.forEach(content => {
                content.classList.remove('show', 'active');
            });
            document.getElementById(targetId).classList.add('show', 'active');
        });
    });
});



document.querySelector('.sidebar').addEventListener('mouseover', function() {
    document.querySelector('.default-content').classList.add('blur-effect'); // モザイク効果を追加
    document.querySelector('.tab-content').classList.add('blur-effect');    // モザイク効果を追加
});

document.querySelector('.sidebar').addEventListener('mouseout', function() {
    document.querySelector('.default-content').classList.remove('blur-effect'); // モザイク効果を削除
    document.querySelector('.tab-content').classList.remove('blur-effect');     // モザイク効果を削除
});
