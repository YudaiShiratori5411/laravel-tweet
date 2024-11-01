// ページ読み込み時にダークモードを適用する
document.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        document.body.classList.add("dark-mode");
        document.getElementById("theme-toggle").textContent = "ライトモードに切り替え";
    } else {
        document.getElementById("theme-toggle").textContent = "ダークモードに切り替え";
    }
});

// ダークモードの切り替え関数
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    const isDarkMode = document.body.classList.contains("dark-mode");
    localStorage.setItem("theme", isDarkMode ? "dark" : "light");
    document.getElementById("theme-toggle").textContent = isDarkMode ? "ライトモードに切り替え" : "ダークモードに切り替え";
}







// 言語設定の変更関数
function changeLanguage() {
    const language = document.getElementById("language-select").value;
    localStorage.setItem("language", language);
    // 必要に応じて、ページの再読み込みや、特定の要素を翻訳する処理を追加
}




