<?php
session_start();

if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];

    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Alterner entre 'true' et 'false' pour la mise en ligne de l'offre.
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $est_en_ligne = $stmt->fetch(PDO::FETCH_ASSOC)['est_en_ligne'];

    if ($est_en_ligne) {
        $stmt = $dbh->prepare("UPDATE sae_db._offre SET est_en_ligne = FALSE WHERE id_offre = :id_offre");
        $stmt->bindParam(':id_offre', $id_offre);
    } else {
        $stmt = $dbh->prepare("UPDATE sae_db._offre SET est_en_ligne = TRUE WHERE id_offre = :id_offre");
        $stmt->bindParam(':id_offre', $id_offre);
    }
    $stmt->execute();
    $est_en_ligne = $est_en_ligne ? false : true;

    $stmt = $dbh->prepare("INSERT INTO sae_db._log_changement_status (id_offre, enligne) VALUES (:id_offre, {($est_en_ligne ? 'false' : 'true')}) RETURNING enligne");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $enligne = $stmt->fetch(PDO::FETCH_ASSOC)[0]['enligne'];
    
    echo $enligne;
}
header('Location: /pro');
exit();
