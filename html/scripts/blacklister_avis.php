<?php
session_start();

// Connexion à la BDD
require_once $_SERVER['DOCUMENT_ROOT'] . '/../php_files/connect_to_bdd.php';

// GET data
$id_avis = $_POST['id_avis'] ?? '';
$jours = isset($_POST['jours']) && is_numeric($_POST['jours']) ? (int) $_POST['jours'] : 0;
$heures = isset($_POST['heures']) && is_numeric($_POST['heures']) ? (int) $_POST['heures'] : 0;
$minutes = isset($_POST['minutes']) && is_numeric($_POST['minutes']) ? (int) $_POST['minutes'] : 0;
$secondes = isset($_POST['secondes']) && is_numeric($_POST['secondes']) ? (int) $_POST['secondes'] : 0;

// Construire l'intervalle de manière sécurisée
$intervalle = "{$jours} days {$heures} hours {$minutes} minutes {$secondes} seconds";

// Vérifier que l'intervalle est bien formé (optionnel, mais recommandé)
if (!preg_match('/^\d+ days \d+ hours \d+ minutes \d+ seconds$/', $intervalle)) {
    echo "Intervalle invalide.";
    exit;
}

// Essayer de blacklister l'avis
try {
    $dbh->beginTransaction();
    $query = "UPDATE sae_db._avis SET fin_blacklistage = CURRENT_TIMESTAMP + INTERVAL '$intervalle' WHERE id_avis = :id_avis";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id_avis', $id_avis, PDO::PARAM_INT);
    $stmt->execute();
    $dbh->commit();
} catch (Exception $e) {
    echo 'Erreur lors du blacklistage de l\'avis n°' . $id_avis;
    echo $e->getMessage();
    $dbh->rollBack();
    exit();
}

// Tout s'est bien passé
$_SESSION['message_pour_notification'] = 'L\'avis a bien été blacklisté';
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: /');
}
