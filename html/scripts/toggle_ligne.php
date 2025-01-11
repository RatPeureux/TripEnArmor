<?php
session_start();

if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];

    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Alterner entre 'true' et 'false' pour la mise en ligne de l'offre.
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre NATURAL JOIN sae_db._type_offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_valeur_en_ligne = $offre['est_en_ligne'] ? 'false' : 'true';
    $type_offre = $offre['nom'];

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

    // Ajouter ou modifier une ligne de la table _periodes_en_ligne pour garder un historique
        // Si l'offre passe en ligne, créer une nouvelle période
    if($next_valeur_en_ligne) {
        $stmt = $dbh->prepare("INSERT INTO sae_db._periodes_en_ligne (id_offre, type_offre, date_fin) VALUES (?, ?, NULL)");
        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $type_offre);
    }
        // Si l'offre passe hors ligne, compléter la achever la période existante (normalement, une seule ligne avec date_fin = null)
    else {
        $stmt = $dbh->prepare("UPDATE _periodes_en_ligne
                                      SET date_fin = CURRENT_DATE
                                      WHERE id_offre = ? and date_fin is null");
        $stmt->bindParam(1, $id_offre);
    }
}

header('Location: /pro');
exit();
