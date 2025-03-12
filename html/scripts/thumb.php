<?php
session_start();
header('Content-Type: application/json');

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

$id_membre = $_SESSION['id_membre'] ?? null;
$id_avis = $_GET['id_avis'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id_membre || !$id_avis || !$action) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

try {
    if ($action == 'up' || $action == 'down') {
        $query = "INSERT INTO sae_db._avis_reactions (id_membre, id_avis, type_de_reaction) VALUES (?, ?, ?)";

        $statement = $dbh->prepare($query);
        $type_de_reaction = ($action == 'up');

        $statement->bindParam(1, $id_membre);
        $statement->bindParam(2, $id_avis);
        $statement->bindParam(3, $type_de_reaction, PDO::PARAM_BOOL);

        if ($statement->execute()) {
            echo json_encode(['success' => true, 'message' => 'Réaction ajoutée']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l’ajout de la réaction']);
        }
    } elseif ($action == 'upTOdown' || $action == 'downTOup') {
        $query = "UPDATE sae_db._avis_reactions SET type_de_reaction = ? WHERE id_membre = ? AND id_avis = ?";

        $statement = $dbh->prepare($query);
        $type_de_reaction = ($action == 'downTOup');

        $statement->bindParam(1, $type_de_reaction, PDO::PARAM_BOOL);
        $statement->bindParam(2, $id_membre);
        $statement->bindParam(3, $id_avis);

        if ($statement->execute()) {
            echo json_encode(['success' => true, 'message' => 'Réaction mise à jour']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de la réaction']);
        }
    } elseif ($action == 'upTOnull' || $action == 'downTOnull') {
        $query = "DELETE FROM sae_db._avis_reactions WHERE id_membre = ? AND id_avis = ?";

        $statement = $dbh->prepare($query);
        $statement->bindParam(1, $id_membre);
        $statement->bindParam(2, $id_avis);

        if ($statement->execute()) {
            echo json_encode(['success' => true, 'message' => 'Réaction supprimée']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de la réaction']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action inconnue']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur interne : ' . $e->getMessage()]);
}