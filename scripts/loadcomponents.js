// Charger les composants HTML statiques ()
async function loadFooterHeader() {
    // Charger les fichiers des composants
    const footerHTML = await fetch('./components/footer.html').then(response => response.text());
    const footerProHTML = await fetch('./components/footer-pro.html').then(response => response.text());
    const headerHTML = await fetch('./components/header.html').then(response => response.text());
    const menuHTML = await fetch('./components/menu.html').then(response => response.text());
    const headerProHTML = await fetch('./components/header-pro.html').then(response => response.text());
    const menuProHTML = await fetch('./components/menu-pro.html').then(response => response.text());

    let headers = document.querySelectorAll('#header');
    let footer = document.getElementById('footer');
    let footerPro = document.getElementById('footer-pro');
    let menus = document.querySelectorAll('#menu');
    let headerPro = document.getElementById('header-pro');
    let menuPro = document.getElementById('menu-pro');

    // Ajouter les textes aux divs
    for (const header of headers) {
        header.innerHTML = headerHTML;
    }
    if (footer) {
        footer.innerHTML = footerHTML;
    }
    if (footerPro) {
        footerPro.innerHTML = footerProHTML;
    }
    for (const menu of menus) {
        menu.innerHTML = menuHTML;
    }
    if (headerPro) {
        headerPro.innerHTML = headerProHTML;
    }
    if (menuPro) {
        menuPro.innerHTML = menuProHTML;
    }

    // Ajouter la classe 'active' à l'item du menu sur lequel l'utilisateur se trouve (où il est dans la navigation)
        // Visiteur / membre
    for (const menu of menus) {
        let activeMenuItemIdx = parseInt(menu.classList.toString());
        let allItemsSections = menu.querySelectorAll('div.all-items');
        if (activeMenuItemIdx) {
            for (const allItems of allItemsSections) {
                let activeItem = allItems.querySelector(`:nth-child(${activeMenuItemIdx})`);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }
        }
    }
        // Pro
    let activeMenuItemIdx = parseInt(menuPro.classList.toString());
    let allItems = menuPro.querySelector('div.all-items');
    if (activeMenuItemIdx) {
        let activeItem = allItems.querySelector(`:nth-child(${activeMenuItemIdx})`);
        if (activeItem) {
            activeItem.classList.add('active');
        }
    }
}

loadFooterHeader();

