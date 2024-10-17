// To load static components that can be repeated in different pages (written in html)
async function loadFooterHeader() {
    // Load components
    const footerHTML = await fetch('./components/footer.html').then(response => response.text());
    const headerHTML = await fetch('./components/header.html').then(response => response.text());
    const menuHTML = await fetch('./components/menu.html').then(response => response.text());

    let headers = document.querySelectorAll("#header");
    let footer = document.getElementById("footer");
    let menus = document.querySelectorAll("#menu");

    // Add text
    for (const header of headers) {
        header.innerHTML = headerHTML;
    }
    footer.innerHTML = footerHTML;
    for (const menu of menus) {
        menu.innerHTML = menuHTML;
    }
    
    // Put active class to the given element (number in class)
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
}

loadFooterHeader();

