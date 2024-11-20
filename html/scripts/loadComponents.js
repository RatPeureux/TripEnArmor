async function retrieveElement(id) {
    const value = await fetch('/public/components/' + id + '.php').then(response => response.text());
    let element = document.getElementById(id);
    if (element) {
        element.innerHTML = value;
    }
    return element
}

const footerHTML = await fetch('/public/components/footer.php').then(response => response.text());
const headerHTML = await fetch('/public/components/header.php').then(response => response.text());
const menuHTML = await fetch('/public/components/menu.php').then(response => response.text());

let footer = await retrieveElement('footer');
let headers = document.querySelectorAll('#header');
let menus = document.querySelectorAll('#menu');

for (const header of headers) {
    header.innerHTML = headerHTML;
}

for (const menu of menus) {
    menu.innerHTML = menuHTML;
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
}

