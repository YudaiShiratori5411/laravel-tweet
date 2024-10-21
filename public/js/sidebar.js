document.querySelectorAll('.sidebar').forEach(sidebar => {
    sidebar.addEventListener('mouseenter', function() {
        sidebar.classList.add('sidebar-expanded');
    });

    sidebar.addEventListener('mouseleave', function() {
        sidebar.classList.remove('sidebar-expanded');
    });
});
s
