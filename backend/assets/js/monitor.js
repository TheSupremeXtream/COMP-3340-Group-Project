// Wait until the page has finished loading
document.addEventListener('DOMContentLoaded', () => {
    // Get the timer element from the monitor page
    const timerEl = document.getElementById('refresh-timer');

    // Stop running if the timer element does not exist
    if (!timerEl) {
        return;
    }

    // Set the starting countdown time in seconds
    let countdown = 60;
    // Show the starting countdown message
    timerEl.textContent = `Auto-refreshing in ${countdown}s…`;

    // Run the countdown once every second
    const tick = setInterval(() => {
        // Decrease the countdown by 1 second
        countdown -= 1;
        // Update the visible timer text
        timerEl.textContent = `Auto-refreshing in ${countdown}s…`;

        // Refresh the page when the countdown reaches 0
        if (countdown <= 0) {
            clearInterval(tick);
            window.location.reload();
        }
    }, 1000);
});