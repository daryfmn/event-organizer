document.addEventListener('submit', (event) => {
    if (event.defaultPrevented) {
        return;
    }

    event.target.querySelectorAll('button[type="submit"], button:not([type])').forEach((button) => {
        button.disabled = true;
    });
});

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        document.querySelectorAll('button:disabled').forEach((button) => {
            button.disabled = false;
        });
    }
});
