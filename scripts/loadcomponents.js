async function loadFooterHeader() {
    // Load components
    const footerHTML = await fetch('./components/footer.html').then(response => response.text());
    const headerHTML = await fetch('./components/header.html').then(response => response.text());
    const menuHTML = await fetch('./components/menu.html').then(response => response.text());

    let header = document.getElementById("header");
    let footer = document.getElementById("footer");
    let menu = document.getElementById("menu");

    
    // Add text
    header.innerHTML = headerHTML;
    footer.innerHTML = footerHTML;
    menu.innerHTML = menuHTML;
    
    // Put active class to the given element (number in class)
    let activeMenuItemIdx = parseInt(menu.classList.toString());
    let allItems = menu.querySelector('div.all-items');
    let activeItem = allItems.querySelector(`:nth-child(${activeMenuItemIdx})`);
    if (activeItem) {
        activeItem.classList.add('active');
    }
}

loadFooterHeader();
