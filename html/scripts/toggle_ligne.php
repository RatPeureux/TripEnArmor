<?php
session_start();

if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];

    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    // Controllers
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/periodes_en_ligne_controller.php';
    $periodes_en_ligne_controller = new PeriodesEnLigneController();

    // Alterner entre 'true' et 'false' pour la mise en ligne de l'offre.
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre NATURAL JOIN sae_db._type_offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_valeur_en_ligne = $offre['est_en_ligne'] ? 'false' : 'true';
    $type_offre = $offre['nom'];
    $prix_ht = $offre['prix_ht'];
    $prix_ttc = $offre['prix_ttc'];

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
    if ($next_valeur_en_ligne == 'true') {
        $last_date_fin = $periodes_en_ligne_controller->getLastDateFinByIdOffre($id_offre);

        // Cas de la première création de période en ligne pour cette offre
        if (is_null($last_date_fin)) {
            // Créer nouvelle période en ligne
            $periodes_en_ligne_controller->createPeriodeEnLigne($id_offre, $type_offre, $prix_ht, $prix_ttc);
            return;
        }

        $last_date_fin = new DateTime($last_date_fin);
        $to_compare = new DateTime();
        $to_compare = $to_compare->modify('-2 days');

        // Savoir si la date est antérieur de deux jours au moins par rapport au jour actuel
        if ($last_date_fin <= $to_compare) {
            // Créer nouvelle période en ligne
            $periodes_en_ligne_controller->createPeriodeEnLigne($id_offre, $type_offre, $prix_ht, $prix_ttc);
        } else {
            // Rouvrir la période en ligne dernièrement close (qui date d'hier au moins)
            $periodes_en_ligne_controller->ouvrirPeriodeByIdOffre($id_offre);
        }
    }
    // Si l'offre passe hors ligne, compléter la achever la période existante (normalement, une seule ligne avec date_fin = null)
    else {
        $periodes_en_ligne_controller->clorePeriodeByIdOffre($id_offre);
    }
}

header('Location: /pro');
exit();
