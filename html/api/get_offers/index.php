<?php
header("Content-Type: application/json");

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

$stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true");
$stmt->execute();

$toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($toutesLesOffres);
?>