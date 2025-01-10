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
    $next_valeur_en_ligne = $est_en_ligne ? 'false' : 'true';

    // Changer le satus dans la table correspondante
    $stmt = $dbh->prepare("UPDATE sae_db._offre SET est_en_ligne = :next_valeur_en_ligne WHERE id_offre = :id_offre");
    $stmt->bindParam(':next_valeur_en_ligne', $next_valeur_en_ligne);
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();

    // Ajouter une ligne de changement de status 'en_ligne' dans la table correspondante
    $stmt = $dbh->prepare("INSERT INTO sae_db._log_changement_status (id_offre, en_ligne) VALUES (?, ?)");
    $stmt->bindParam(1, $id_offre);
    $stmt->bindParam(2, $next_valeur_en_ligne);
    $stmt->execute();
}

header('Location: /pro');
exit();
