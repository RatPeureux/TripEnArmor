function toggleMenu() {
    let menu = document.querySelector('#menu>div');
    if (menu) {
        menu.classList.toggle('active');
    }
    let menuPro = document.querySelector('#menu-pro>div');
    if (menuPro) {
        menuPro.classList.toggle('active');
    }
    document.querySelector('#layer-background').classList.toggle('active')
}
window.toggleMenu = toggleMenu;