<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Affecter la réponser dans la BDD
$stmt = $dbh->prepare("UPDATE sae_db._avis SET reponse = NULL where id_avis = ?");
$stmt->bindParam(1, $_GET['id_avis']);
$stmt->execute();

if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: /');
}
