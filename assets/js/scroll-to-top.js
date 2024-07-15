// Show/hide scroll-to-top button based on scroll position
window.addEventListener('scroll', () => {
    const scrollToTopBtn = document.getElementById('btnScrollToTop');
    if (window.scrollY > 300) {
        scrollToTopBtn.style.display = 'block';
    } else {
        scrollToTopBtn.style.display = 'none';
    }
});

// Smooth scroll to top functionality
document.getElementById('btnScrollToTop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});