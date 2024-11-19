<?php
session_start();

if (isset($_GET['offre_id'])) {
    $offre_id = $_GET['offre_id'];

    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php-files/connect_to_bdd.php';

    // Alterner entre 'true' et 'false' pour la mise en ligne de l'offre.
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE offre_id = :offre_id");
    $stmt->bindParam(':offre_id', $offre_id);
    $stmt->execute();
    $est_en_ligne = $stmt->fetch(PDO::FETCH_ASSOC)['est_en_ligne'];

    if ($est_en_ligne) {
        $stmt = $dbh->prepare("UPDATE sae_db._offre SET est_en_ligne = FALSE WHERE offre_id = :offre_id");
        $stmt->bindParam(':offre_id', $offre_id);
    } else {
        $stmt = $dbh->prepare("UPDATE sae_db._offre SET est_en_ligne = TRUE WHERE offre_id = :offre_id");
        $stmt->bindParam(':offre_id', $offre_id);
    }
    $stmt->execute();

    echo $est_en_ligne;
}
header('Location: /pro');
exit();
