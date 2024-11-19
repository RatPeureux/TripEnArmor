function confirmLogout(event) {
    // Affiche une boîte de confirmation
    const confirmation = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    
    // Si l'utilisateur annule, on empêche l'action par défaut (la déconnexion)
    if (!confirmation) {
        event.preventDefault();
    }

    // Si l'utilisateur confirme, l'action par défaut (déconnexion) continue
    return confirmation;
}