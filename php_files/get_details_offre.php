<?php

// Obtenir le nom du pro
$id_pro = $offre['id_pro'];

require_once dirname(path: $_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_prive_controller.php";
$result = [
    "id_compte" => "",
    "nom_pro" => "",
    "email" => "",
    "tel" => "",
    "id_adresse" => "",
    "data" => [
    ]
];
$proController = new ProPriveController();
$pro = $proController->getInfosProPrive($id_pro);

if (!$pro) {
    require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_public_controller.php";
    $proController = new ProPublicController();
    $pro = $proController->getInfosProPublic($id_pro);

    $pro["data"]["type_orga"] = $pro["type_orga"];
    $pro["data"]["type"] = "public";

    // Si aucun pro n'est trouvé avec cet identifiant
    if (!$pro) {
        header('location: /pro/connexion');
        exit();
    }
} else {
    $pro["data"]["numero_siren"] = $pro["num_siren"];
    $pro["data"]["id_rib"] = $pro["id_rib"];
    $pro["data"]["type"] = "prive";
}


// Détails globaux de l'offre
$id_offre = $offre['id_offre'];
$description = $offre['description'];
$resume = $offre['resume'];
$est_en_ligne = $offre['est_en_ligne'];
$date_mise_a_jour = $offre['date_mise_a_jour'];
$titre_offre = $offre['titre'];


// Otenir la moyenne des notes de l'offre
$stmt = $dbh->prepare("SELECT avg, count FROM sae_db.vue_moyenne WHERE id_offre = :id_offre");
$stmt->bindParam(':id_offre', $id_offre);
$stmt->execute();
$moyenne = $stmt->fetch(PDO::FETCH_ASSOC);
if ($moyenne) {
    $nb_avis = intval($moyenne['count']);
    $moyenne = floatval($moyenne['avg']);
}

// Obtenir la catégorie de l'offre
$stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_categorie WHERE id_offre = :id_offre");
$stmt->bindParam(':id_offre', $id_offre);
$stmt->execute();
$categorie_offre = $stmt->fetch(PDO::FETCH_ASSOC)['type_offre'];


// Obtenir les dates
$est_en_ligne = $offre['est_en_ligne'];
$date_mise_a_jour = $offre['date_mise_a_jour'];
if (isset($date_mise_a_jour) && $date_mise_a_jour) {
    $date_mise_a_jour = new DateTime($date_mise_a_jour);
    $date_mise_a_jour = $date_mise_a_jour->format('d/m/Y');
}


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
$stmt->bindParam(':id_offre', var: $id_offre);
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
} else if ($tarif_min && $tarif_max) {
    $prix_a_afficher = $tarif_min . '-' . $tarif_max . '€';
} else {
    // Edge case: offre sans aucun tarif
    $prix_a_afficher = "Gratuit";
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
        $stmt->bindParam(':id_repas', $id_repas['id_type_repas']);
        $stmt->execute();
        $nom = $stmt->fetch(PDO::FETCH_ASSOC)['nom'];
        $tags = $tags . ', ' . $nom;
    }
    $tags_type_repas = $tags;

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

// Souscriptions d'options de l'offre
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/souscription_controller.php';
$souscription_controller = new SouscriptionController();
$souscriptions_options = $souscription_controller->getAllSouscriptionsByIdOffre($id_offre);

$option = false;
foreach($souscriptions_options as $souscription) {
    // $souscription est un tableau associatif avec une clé "date_lancement" et une clé "nb_semaines". Il faudrait calculer si une option est actuellement active. Si oui, on met la variable $option à true.
    $date_lancement = new DateTime($souscription['date_lancement']);
    $date_fin = clone $date_lancement;
    $date_fin->modify('+' . $souscription['nb_semaines'] . ' weeks');
    $now = new DateTime();

    if ($now >= $date_lancement && $now <= $date_fin) {
        $option = true;
        break;
    }
}