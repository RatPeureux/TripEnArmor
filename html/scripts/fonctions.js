// Configurer les flèches pour faire des dropdown menu stylés
function setupToggle(arrowID, buttonID, infoID) {
    const button = document.getElementById(buttonID);
    const arrow = document.getElementById(arrowID);
    const info = document.getElementById(infoID);
    arrow.classList.toggle('rotate-90');

    if (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            arrow.classList.toggle('rotate-90');
            info.classList.toggle('hidden');
        });
    }
}
window.setupToggle = setupToggle;
