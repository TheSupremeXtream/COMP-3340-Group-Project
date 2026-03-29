// Find all links that should play the whoosh sound
document.addEventListener('DOMContentLoaded', () => {
    const whoosh = document.getElementById('whoosh');

    document.querySelectorAll('.whoosh-link').forEach(link => {
        // Add a click event listener to each link
        link.addEventListener('click', function (e) {
            if (!whoosh) return;

            // Prevent the page from changing immediately
            e.preventDefault();

            // Restart the sound from the beginning
            whoosh.currentTime = 0;
            // Play the sound effect
            whoosh.play().catch(() => {});
            
            // Wait briefly before navigating to the next page
            setTimeout(() => {
                window.location.href = this.href;
            }, 350);
        });
    });
});