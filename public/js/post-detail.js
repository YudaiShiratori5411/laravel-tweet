document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.options-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation(); // 外部クリック時に閉じる
            const optionsList = button.nextElementSibling;
            optionsList.classList.toggle('visible'); // 表示・非表示を切り替える
        });
    });

    // 画面のどこかをクリックするとオプションリストを閉じる
    document.addEventListener('click', () => {
        document.querySelectorAll('.options-list').forEach(list => {
            list.classList.remove('visible');
        });
    });
});



// ページが読み込まれたときに削除ボタンにクリックイベントを追加
document.addEventListener("DOMContentLoaded", function() {
    const deleteButton = document.getElementById("delete-button");
    const deleteForm = document.getElementById("delete-form");

    deleteButton.addEventListener("click", function() {
        if (confirm("本当に削除しますか？")) {
            deleteForm.submit();
        }
    });
});
