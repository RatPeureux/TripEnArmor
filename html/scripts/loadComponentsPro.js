async function retrieveElement(id) {
    const value = await fetch('/public/components/' + id + '.php').then(response => response.text());
    let element = document.getElementById(id);
    if (element) {
        element.innerHTML = value;
    }
    return element
}

let headerPro = await retrieveElement('header-pro');
let footerPro = await retrieveElement('footer-pro');
let menuPro = await retrieveElement('menu-pro');

// Ajouter la classe 'active' à l'item du menu sur lequel l'utilisateur se trouve (où il est dans la navigation)
let activeMenuItemIdx = parseInt(menuPro.classList.toString());
let allItemsSections = menuPro.querySelectorAll('div.all-items');
if (activeMenuItemIdx) {
    for (const allItems of allItemsSections) {
        let activeItem = allItems.querySelector(`:nth-child(${activeMenuItemIdx})`);
        if (activeItem) {
            activeItem.classList.add('active');
        }
    }
}
