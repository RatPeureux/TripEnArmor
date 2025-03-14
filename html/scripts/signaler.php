<?php
// Type de contenu en JSON
header('Content-Type: application/json');

// GET data
$motif = isset($_GET['motif']) ? $_GET['motif'] : '';
$commentaireSignalement = isset($_GET['commentaireSignalement']) ? $_GET['commentaireSignalement'] : '';
$dateTime = isset($_GET['dateTime']) ? $_GET['dateTime'] : '';
$id_avis = isset($_GET['id_avis']) ? $_GET['id_avis'] : '';

// Vérification des champs obligatoires
if (empty($motif) || empty($dateTime) || empty($id_avis)) {
    echo json_encode(['error' => 'Champs manquants.']);
    exit();
}

// Fichier dans lequel écrire
$file = $_SERVER['DOCUMENT_ROOT'] . '/../signalements.txt';

// Créer le répertoire parent si nécessaire
$directory = dirname($file);
if (!is_dir($directory)) {
    if (!mkdir($directory, 0755, true)) {
        echo json_encode(['error' => 'Impossible de créer le répertoire pour les signalements.']);
        exit();
    }
}

// Préparer le contenu à écrire
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

// Réponse de succès
echo json_encode(['success' => true, 'message' => 'Avis signalé avec succès. Merci pour votre contribution.']);