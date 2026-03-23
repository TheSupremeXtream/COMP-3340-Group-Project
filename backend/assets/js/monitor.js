document.addEventListener('DOMContentLoaded', () => {
    const timerEl = document.getElementById('refresh-timer');

    if (!timerEl) {
        return;
    }

    let countdown = 60;
    timerEl.textContent = `Auto-refreshing in ${countdown}s…`;

    const tick = setInterval(() => {
        countdown -= 1;
        timerEl.textContent = `Auto-refreshing in ${countdown}s…`;

        if (countdown <= 0) {
            clearInterval(tick);
            window.location.reload();
        }
    }, 1000);
});