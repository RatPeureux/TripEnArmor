function toggleMenu() {
    document.querySelector('#menu>div').classList.toggle('active');
    document.querySelector('#layer-background').classList.toggle('active')
}
window.toggleMenu = toggleMenu;