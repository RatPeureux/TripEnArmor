// Activer les menus de détails de consultation / modification pour chaque offre
const allDetailsMenus = document.querySelectorAll('.details-menu');
const allDetailsMenuToggles = document.querySelectorAll('.details-menu-toggle');
for (let i=0; i<allDetailsMenus.length; i++) {
    const menu = allDetailsMenus[i];
    const toggle = allDetailsMenuToggles[i];
    // Afficher le menu quand on clique sur les 3 petis points
    toggle.addEventListener('click', function(event) {
        event.preventDefault();
        menu.classList.remove('hidden');
    });
    // Retirer les menus quand on clique autre part
    document.addEventListener('click', function(event) {
        if (event.target !== toggle && event.target !== menu) {
            menu.classList.add('hidden');
        }
    });
}
