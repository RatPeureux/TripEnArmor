<?php
// Connexion à la BDD
require_once $_SERVER['DOCUMENT_ROOT'] . '/../php_files/connect_to_bdd.php';

// GET data
$id_avis = isset($_GET['id_avis']) ? $_GET['id_avis'] : '';
$duree_blacklistage = isset($_GET['duree_blacklistage']) ? $_GET['duree_blacklistage'] : '';

// Champs
if (empty($id_avis) || empty($duree_blacklistage)) {
    echo json_encode(['error' => 'Champs manquants.']);
}

// Essayer de blacklister l'avis dans la base de données
try {
  $dbh->beginTransaction();
  $stmt = $dbh->prepare("UPDATE sae_db._avis SET fin_blacklistage = CURRENT_DATE + :nb_jours::INTEGER WHERE id_avis = :id_avis");
  $stmt->bindParam(':nb_jours', $duree_blacklistage);
  $stmt->bindParam(':id_avis', $id_avis);
  $stmt->execute();
  $dbh->commit();
} catch (Exception $e) {
  echo 'Erreur lors du blacklistage de l\'avis n°' . $id_avis;
  echo $e->getMessage();
  $dbh->rollBack();
}

// Tout s'est bien passé
if (isset($_SERVER['HTTP_REFERER'])) {
  header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
  header('Location: /');
}

