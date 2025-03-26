<?php
session_start();

$id_avis = $_GET['id_avis'];
$id_offre = $_GET['id_offre'];

// Enlever les avis restauration (notes détaillées) si besoin
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';
$restaurationController = new RestaurationController();
$restauration = $restaurationController->getInfosRestauration($id_offre);

if ($restauration) {
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    $stmt = $dbh->prepare("DELETE FROM sae_db._avis_restauration_note WHERE id_avis = :id_avis AND id_restauration = :id_restauration");
    $stmt->bindParam(":id_avis", $id_avis);
    $stmt->bindParam(":id_restauration", $restauration['id_offre']);
    $stmt->execute();
}

// Supprimer l'avis
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
$avisController = new AvisController();
$avisController->deleteAvis($id_avis);


// Tout s'est bien passé
$_SESSION['message_pour_notification'] = 'Votre avis a été supprimé';
if (isset($_SERVER['HTTP_REFERER'])) {
    // Revenir à la page sur laquelle on était
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Si il est impossible de connaître la page précédente, alors revenir sur la page de l'offre où il y avait l'avis
    header("location: /offre?id=$id_offre");
    exit();
}
