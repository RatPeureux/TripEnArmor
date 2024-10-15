export async function loadFooterHeader() {
    // Load components
    const footerHTML = await fetch('components/footer').then(response => response.text());
    const headerHTML = await fetch('components/header').then(response => response.text());

    let header = document.getElementById("header");
    let footer = document.getElementById("footer");

    // Add text
    header.innerHTML = headerHTML;
    footer.innerHTML = footerHTML;
}
