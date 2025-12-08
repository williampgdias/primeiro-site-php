document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('theme-toggle');
    const body = document.body;

    if (localStorage.getItem('tema') === 'dark') {
        body.classList.add('dark-mode');
        toggleBtn.textContent = '☀️'; // Sol
    }

    toggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');

        if (body.classList.contains('dark-mode')) {
            toggleBtn.textContent = '☀️';
            localStorage.setItem('tema', 'dark');
        } else {
            toggleBtn.textContent = '🌙';
            localStorage.setItem('tema', 'light');
        }
    });
});
