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

const menuButton = document.getElementById('user-menu-btn');
const userMenu = document.getElementById('user-dropdown');

if (menuButton && userMenu) {
    menuButton.addEventListener('click', (event) => {
        event.stopPropagation();
        userMenu.classList.toggle('open');
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.user-menu')) {
            userMenu.classList.remove('open');
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            userMenu.classList.remove('open');
        }
    });
}
