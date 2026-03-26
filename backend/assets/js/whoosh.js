document.addEventListener('DOMContentLoaded', () => {
    const whoosh = document.getElementById('whoosh');

    document.querySelectorAll('.whoosh-link').forEach(link => {
        link.addEventListener('click', function (e) {
            if (!whoosh) return;

            e.preventDefault();

            whoosh.currentTime = 0;
            whoosh.play().catch(() => {});

            setTimeout(() => {
                window.location.href = this.href;
            }, 350);
        });
    });
});