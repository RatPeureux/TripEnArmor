<?php

// Détails globaux de l'offre
$offre_id = $offre['offre_id'];
$description = $offre['description_offre'];
$resume = $offre['resume_offre'];
$option = $offre['option'];
$est_en_ligne = $offre['est_en_ligne'];
$date_mise_a_jour = $offre['date_mise_a_jour'];
$titre_offre = $offre['titre'];

// Obtenir la catégorie de l'offre
$stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_categorie WHERE offre_id = :offre_id");
$stmt->bindParam(':offre_id', $offre_id);
$stmt->execute();
$categorie_offre = $stmt->fetch(PDO::FETCH_ASSOC)['type_offre'];

// Obtenir la date de mise à jour
$est_en_ligne = $offre['est_en_ligne'];
$date_mise_a_jour = $offre['date_mise_a_jour'];
$date_mise_a_jour = new DateTime($date_mise_a_jour);
$date_mise_a_jour = $date_mise_a_jour->format('d/m/Y');

// Obtenir le type de l'offre (gratuit, standard, premium)
$stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_type WHERE offre_id = :offre_id");
$stmt->bindParam(':offre_id', $offre_id);
$stmt->execute();
$type_offre = $stmt->fetch(PDO::FETCH_ASSOC)['nom_type_offre'];

// Détails de l'adresse
$adresse_id = $offre['adresse_id'];
$stmt = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE adresse_id = :adresse_id");
$stmt->bindParam(':adresse_id', $adresse_id);
$stmt->execute();
$adresse = $stmt->fetch(PDO::FETCH_ASSOC);
$code_postal = $adresse['code_postal'];
$ville = $adresse['ville'];

// #################################################################################
// ######### CAS DES AFFICHAGES QUI DIFFÈRENT SELON LA CATÉGORIE DE L'OFFRE #########
// #################################################################################

// Afficher les prix ou la gamme de prix si c'est un restaurant
// 1. Obtenir prix minimal & maximal (sert pour détails de l'offre)
$stmt = $dbh->prepare("SELECT * FROM sae_db._tarif_public WHERE offre_id = :offre_id");
$stmt->bindParam(':offre_id', $offre_id);
$stmt->execute();
$allTarifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tarif_min = 99999;
$tarif_max = 0;
if ($allTarifs) {
    foreach ($allTarifs as $tarif) {
        if ($tarif['prix'] > $max_tarif_max) {
            $tarif_max = $tarif['prix'];
        }
        if ($tarif['prix'] < $tarif_min) {
            $tarif_min = $tarif['prix'];
        }
    }
} else {
    $tarif_min = '';
    $tarif_max = '';
}
$prix_a_afficher;
if ($categorie_offre == 'restauration') {
    $stmt = $dbh->prepare("SELECT * FROM sae_db._restauration WHERE offre_id = :offre_id");
    $stmt->bindParam(':offre_id', $offre_id);
    $stmt->execute();
    $prix_a_afficher = $stmt->fetch(PDO::FETCH_ASSOC)['gamme_prix'];
} else {
    $prix_a_afficher = $tarif_min . '-' . $tarif_max . '€';
}

// Tags pour le restaurant (pour la carte, on prend les types de repas) ou autres si ce n'est pas un restaurant
if ($categorie_offre == 'restauration') {
    $stmt = $dbh->prepare("SELECT type_repas_id FROM sae_db._restaurant_type_repas WHERE restauration_id = :offre_id");
    $stmt->bindParam(':offre_id', $offre_id);
    $stmt->execute();
    $repasIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tags = '';
    // Récup chaque nom de tag, et l'ajouter aux tags
    foreach ($repasIds as $repasId) {
        $stmt = $dbh->prepare("SELECT nom_type_repas FROM sae_db._type_repas WHERE type_repas_id = :repasId");
        $stmt->bindParam(':repasId', $repasId);
        $stmt->execute();
        $nom_tag = $stmt->fetch(PDO::FETCH_ASSOC);
        $tags = $tags . ', ' . $nom_tag;
    }
    // Tags pour les autres types d'offre
} else {
    $stmt = $dbh->prepare("SELECT tag_id FROM sae_db._tag_$categorie_offre WHERE id_$categorie_offre = :offre_id");
    $stmt->bindParam(':offre_id', $offre_id);
    $stmt->execute();
    $tagIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tags = '';
    // Récup chaque nom de tag, et l'ajouter aux tags
    foreach ($tagIds as $tagId) {
        $stmt = $dbh->prepare("SELECT nom_tag FROM sae_db._tag WHERE tag_id = :tagId");
        $stmt->bindParam(':tagId', $tagId);
        $stmt->execute();
        $nom_tag = $stmt->fetch(PDO::FETCH_ASSOC);
        $tags = $tags . ', ' . $nom_tag;
    }
}
