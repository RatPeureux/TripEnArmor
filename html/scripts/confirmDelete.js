export function confirmDelete() {
  const confirmation = confirm(
    "Êtes-vous sûr de vouloir supprimer votre compte ?"
  );
  // Si l'utilisateur confirme, l'action par défaut (suppression) continue
  return confirmation;
}
window.confirmDelete = confirmDelete;
