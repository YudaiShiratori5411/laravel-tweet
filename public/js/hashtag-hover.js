document.querySelectorAll('.hashtag-item').forEach(item => {
    item.addEventListener('mouseover', () => {
        item.style.backgroundColor = '#f8f9fa'; // Slight background change on hover
    });

    item.addEventListener('mouseout', () => {
        item.style.backgroundColor = ''; // Reset background on mouse out
    });
});
