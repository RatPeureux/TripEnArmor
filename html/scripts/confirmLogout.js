export function confirmLogout() {
    const confirmation = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    // Si l'utilisateur confirme, l'action par défaut (déconnexion) continue
    return confirmation;
}
window.confirmLogout = confirmLogout;