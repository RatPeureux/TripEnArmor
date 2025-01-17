<?php
// Type de contenu en JSON
header('Content-Type: application/json');

// GET data
$motif = isset($_GET['motif']) ? $_GET['motif'] : '';
$commentaireSignalement = isset($_GET['commentaireSignalement']) ? $_GET['commentaireSignalement'] : '';
$dateTime = isset($_GET['dateTime']) ? $_GET['dateTime'] : '';
$id_avis = isset($_GET['id_avis']) ? $_GET['id_avis'] : '';

// Champs
if (empty($motif) || empty($dateTime) || empty($id_avis)) {
    echo json_encode(['error' => 'Champs manquants.']);
    exit();
}

// Fichier dans lequel écrire
$file = $_SERVER['DOCUMENT_ROOT'] . '/../signalements.txt';

// Si le fichier existe
if (!is_writable($file)) {
    echo json_encode(['error' => 'Impossible d\'écrire dans le registre de signalements']);
    exit();
}
$content = "$dateTime : avis n°$id_avis pour $motif.";
if (!empty($commentaireSignalement)) {
    $content .= " Commentaire: $commentaireSignalement";
}
$content .= "\n\n";

// Essayer d'écrire sur le fichier signalements.txt
if (file_put_contents($file, $content, FILE_APPEND) === false) {
    echo json_encode(['error' => 'Impossible de signaler l\'avis. Veuillez réessayer ultérieurement.']);
    exit();
}

echo json_encode(['message' => 'Avis signalé avec succès. Merci pour votre contribution.']);
