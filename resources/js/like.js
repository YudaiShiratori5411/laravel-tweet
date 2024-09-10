document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const isLiked = this.classList.contains('liked');

            console.log(`Post ID: ${postId}, Is Liked: ${isLiked}`); // デバッグ用ログ
            console.log(`Fetching URL: /posts/${postId}/like`); // 追加する行
            console.log(`Post ID: ${postId}`); // 追加する行

            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ like: !isLiked })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // サーバーからのレスポンスをログに出力
                if (data.liked !== undefined) {
                    this.classList.toggle('liked', data.liked);
                    const likeCount = this.nextElementSibling;
                    likeCount.textContent = data.likesCount;
                }
            })
            .catch(error => console.error('Error:', error)); // エラーがあればログに出力
        });
    });
});



// document.addEventListener('DOMContentLoaded', function () {
//     document.querySelectorAll('.like-button').forEach(button => {
//         button.addEventListener('click', function () {
//             const postId = this.dataset.postId;
//             const isLiked = this.classList.contains('liked');

//             fetch(`/posts/${postId}/like`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                 },
//                 body: JSON.stringify({ like: !isLiked })
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     this.classList.toggle('liked');
//                     const likeCount = this.nextElementSibling;
//                     likeCount.textContent = data.likes_count;
//                 }
//             });
//         });
//     });
// });




// $(document).ready(function() {
//     $(document).on('click', '.like-button', function(e) {
//         e.preventDefault();
//         var postId = $(this).data('post-id');
//         var likeButton = $(this);
//         var likesCountSpan = $('#likes-count-' + postId);

//         $.ajax({
//             url: '/like/' + postId,
//             method: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             success: function(response) {
//                 if (response.liked) {
//                     likeButton.addClass('liked');
//                     likesCountSpan.text(parseInt(likesCountSpan.text()) + 1);
//                 } else {
//                     likeButton.removeClass('liked');
//                     likesCountSpan.text(parseInt(likesCountSpan.text()) - 1);
//                 }
//             },
//             error: function(xhr) {
//                 console.log('Error:', xhr);
//             }
//         });
//     });
// });
