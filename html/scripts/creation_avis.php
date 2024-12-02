<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
$avisController = new AvisController;

// Obtenir les informations nécessaires pour la création de l'avis
$titre = isset($_POST['titre']) ? $_POST['titre'] : 'Avis';
$commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';

$note = isset($_POST['note_globale']) ? floatval($_POST['note_globale']) : 2.5;

$date_experience = isset($_POST['date_experience']) ? $_POST['date_experience'] : date('Y-m-d H:i:s');
$date_experience = date('Y-m-d H:i:s', strtotime($date_experience));
$contexte_passage = isset($_POST['contexte_passage']) ? $_POST['contexte_passage'] : '';

$id_membre = isset($_POST['id_membre']) ? $_POST['id_membre'] : null;
$id_offre = isset($_POST['id_offre']) ? $_POST['id_offre'] : null;

// Créer l'avis dans la BDD
if ($titre && $note && $date_experience && $id_membre && $id_offre) {
    if ($avisController->createAvis($titre, $date_experience, $id_membre, $id_offre, floatval($note), $contexte_passage, $commentaire, null)) {
        header('Location: /offre/index.php');
    }
} else {
    echo "Echec lors de la création de l'avis";
}
