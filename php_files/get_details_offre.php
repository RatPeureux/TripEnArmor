<?php

// Détails globaux de l'offre
$id_offre = $offre['id_offre'];
$description = $offre['description'];
$resume = $offre['resume'];
$option = $offre['option'];
$est_en_ligne = $offre['est_en_ligne'];
$date_mise_a_jour = $offre['date_mise_a_jour'];
$titre_offre = $offre['titre'];


// Obtenir la catégorie de l'offre
$stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_categorie WHERE id_offre = :id_offre");
$stmt->bindParam(':id_offre', $id_offre);
$stmt->execute();
$categorie_offre = $stmt->fetch(PDO::FETCH_ASSOC)['type_offre'];


// Obtenir la date de mise à jour
$est_en_ligne = $offre['est_en_ligne'];
$date_mise_a_jour = $offre['date_mise_a_jour'];
$date_mise_a_jour = new DateTime($date_mise_a_jour);
$date_mise_a_jour = $date_mise_a_jour->format('d/m/Y');

// Obtenir le type de l'offre (gratuit, standard, premium)
$stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_type WHERE id_offre = :id_offre");
$stmt->bindParam(':id_offre', $id_offre);
$stmt->execute();
$type_offre = $stmt->fetch(PDO::FETCH_ASSOC)['nom'];

// Détails de l'adresse
$id_adresse = $offre['id_adresse'];
$stmt = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE id_adresse = :id_adresse");
$stmt->bindParam(':id_adresse', $id_adresse);
$stmt->execute();
$adresse = $stmt->fetch(PDO::FETCH_ASSOC);
$code_postal = $adresse['code_postal'];
$ville = $adresse['ville'];

// #################################################################################
// ######### CAS DES AFFICHAGES QUI DIFFÈRENT SELON LA CATÉGORIE DE L'OFFRE ########
// #################################################################################

// Afficher les prix ou la gamme de prix si c'est un restaurant
// 1. Obtenir prix minimal & maximal (sert pour détails de l'offre)
$stmt = $dbh->prepare("SELECT * FROM sae_db._tarif_public WHERE id_offre = :id_offre");
$stmt->bindParam(':id_offre', $id_offre);
$stmt->execute();
$allTarifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tarif_min = 99999;
$tarif_max = 0;
if ($allTarifs) {
    foreach ($allTarifs as $tarif) {
        if ($tarif['prix'] > $tarif_max) {
            $tarif_max = $tarif['prix'];
        }
        if ($tarif['prix'] < $tarif_min) {
            $tarif_min = $tarif['prix'];
        }
    }
} else {
    $tarif_min = $offre['prix_mini'];
    $tarif_max = '';
}
$prix_a_afficher;
if ($categorie_offre == 'restauration') {
    $stmt = $dbh->prepare("SELECT * FROM sae_db._restauration WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $prix_a_afficher = $stmt->fetch(PDO::FETCH_ASSOC)['gamme_prix'];
} else {
    $prix_a_afficher = $tarif_min . '€';
}
$title_prix = $categorie_offre == 'restauration' ? '€ = X euros, €€ = XX euros, €€€ = XX euros' : 'fourchette des prix';

// Tags pour le restaurant (pour la carte, on prend les types de repas) ou autres si ce n'est pas un restaurant
if ($categorie_offre == 'restauration') {
    $stmt = $dbh->prepare("SELECT id_type_repas FROM sae_db._restaurant_type_repas WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $ids_repas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tags = '';
    // Récup chaque nom de tag, et l'ajouter aux tags
    foreach ($ids_repas as $id_repas) {
        $stmt = $dbh->prepare("SELECT nom FROM sae_db._type_repas WHERE id_type_repas = :id_repas");
        $stmt->bindParam(':id_repas', $id_repas);
        $stmt->execute();
        $nom = $stmt->fetch(PDO::FETCH_ASSOC);
        $tags = $tags . ', ' . $nom;
    }
    // Tags pour les autres types d'offre
} else {
    $stmt = $dbh->prepare("SELECT id_tag FROM sae_db._tag_$categorie_offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $ids_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tags = '';
    // Récup chaque nom de tag, et l'ajouter aux tags
    foreach ($ids_tag as $id_tag) {
        $stmt = $dbh->prepare("SELECT nom FROM sae_db._tag WHERE id_tag = :id_tag");
        $stmt->bindParam(':id_tag', $id_tag);
        $stmt->execute();
        $nom = $stmt->fetch(PDO::FETCH_ASSOC);
        $tags = $tags . ', ' . $nom;
    }
}
