async function retrieveElement(id) {
    const value = await fetch('/public/components/' + id + '.php').then(response => response.text());
    let element = document.getElementById(id);
    if (element) {
        element.innerHTML = value;
    }
    return element
}


let header = await retrieveElement('header');
let footer = await retrieveElement('footer');

let menu = await retrieveElement('menu');

// Ajouter la classe 'active' à l'item du menu sur lequel l'utilisateur se trouve (où il est dans la navigation)
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