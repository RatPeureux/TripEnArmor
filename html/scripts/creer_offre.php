<?php
// Connexion avec la bdd
include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Partie pour traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // *********************************************************************************************************************** Définition de fonctions
    // Fonction pour calculer le prix minimum à partir des prix envoyés dans le formulaire
    function calculerPrixMin($prices)
    {
        $minPrice = null;

        foreach ($prices as $price) {
            if (isset($price['value']) && (is_null($minPrice) || $price['value'] < $minPrice)) {
                $minPrice = $price['value'];
            }
        }

        return $minPrice;
    }

    // Fonction pour extraire des informations depuis une adresse complète
    function extraireInfoAdresse($adresse)
    {
        $numero = substr($adresse, 0, 1);  // À adapter selon le format de l'adresse
        $odonyme = substr($adresse, 2);

        return [
            'numero' => $numero,
            'odonyme' => $odonyme,
        ];
    }

    // *********************************************************************************************************************** Récupération des données du POST
    // Récupération des données du formulaire
    $adresse = $_POST['user_input_autocomplete_address'];
    $code = $_POST['postal_code'];
    $ville = $_POST['locality'];
    $dureeFormatted = sprintf('%02d:%02d:00', $_POST["hours"], $_POST["minutes"]); // Format HH:MM:SS
    // Récupérer d'autres valeurs
    $capacite = $_POST['place'] ?? '';
    $nb_attractions = isset($_POST['parc-numb']) && is_numeric($_POST['parc-numb']) ? (int) $_POST['parc-numb'] : 0;
    $gamme_prix = $_POST['gamme_prix'] ?? '';
    $description = $_POST['description'] ?? '';
    $resume = $_POST['resume'] ?? '';
    $prestations = $_POST['newPrestationName'] ?? '';
    $prices = $_POST['prices'] ?? []; // Récupérer les prix
    $titre = $_POST['titre'] ?? null;
    $tags = $_POST['tags'] ?? [];
    $activity = $_POST['activityType'];

    // TODO: Récupérer l'id du pro, l'id du type d'offre choisi
    $id_pro = $_SESSION['id_pro'];

    // *********************************************************************************************************************** Insertion
    /* Ordre de l'insertion :
    1. [x] Adresse
    2. [x] Tag
    3. [x] Image
    5. Offre
    6. Offre_Tag
    7. Offre_Image
    8. Offre_Langue
    9. Horaires
    10. [x] Tarif_Public
    11. Facture
    */

    // Insérer l'adresse dans la base de données
    $realAdresse = extraireInfoAdresse($adresse);
    require dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/adresse_controller.php';
    $adresseController = new AdresseController();
    $id_adresse = $adresseController->createAdresse($code, $ville, $realAdresse['numero'], $realAdresse['odonyme'], null);
    if (!$id_adresse) {
        echo "Erreur lors de la création de l'adresse.";
        exit;
    }

    // Insérer les tags dans la base de données
    require dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/tag_controller.php';
    $tagController = new TagController();
    $tagIds = [];
    foreach($tags[$activity] as $tag) {
        $tagIds[] = $tagController->createTag($tag);
    }

    // Insérer les image dans la base de données
    require dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/image_controller.php';
    $uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/../public/images/';
    
    $imageController = new ImageController();
    $imageIds = [];
    $imagesIds['carte'] = $imageController->createImage($_POST['photo-upload-carte']);
    foreach($images as $image) {
        $imageIds['details'][] = $imageController->createImage($image);
    }

    $prixMin = calculerPrixMin($prices);
    $id_offre;
    switch ($activity) {
        case 'activite':
            // Insertion spécifique à l'activité
            require dirname($_SERVER['DOCUMENT_ROOT']) .'../controller/activite_controller.php';
            $activiteController = new ActiviteController();
            $activiteController->createActivite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $duree_formatted, $age, $prestations);

            if ($stmtActivite->execute()) {
                echo "Activité insérée avec succès.";
                $stmtTags = $dbh->prepare("INSERT INTO sae_db._tag (nom) VALUES (:tag)");
                $stmtTags->bindParam(':tag', $tag);
                if ($stmtTags->execute()) {
                    $stmtActiviteTag = $dbh->prepare("INSERT INTO sae_db._tag_activite () VALUES ()");
                    if ($stmtActiviteTag->execute()) {
                        echo "Activité insérée avec succès.";
                        header('location: /pro');
                    } else {
                        echo "Erreur lors de l insertion : " . implode(", ", $stmtActiviteTag->errorInfo());
                    }
                } else {
                    echo "Erreur lors de l insertion : " . implode(", ", $stmtTags->errorInfo());
                }


            } else {
                echo "Erreur lors de l insertion : " . implode(", ", $stmtActivite->errorInfo());
            }

            break;

        case 'visite':

            $stmtVisite = $dbh->prepare("INSERT INTO sae_db._visite(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, duree, avec_guide) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :duree, false)");
            $stmtVisite->bindParam(':id_offre', $id_offre);
            $stmtVisite->bindParam(':description', $description);
            $stmtVisite->bindParam(':resume', $resume);
            $stmtVisite->bindParam(':prix', $prix);
            $stmtVisite->bindParam(':date_creation', $dateCreation);
            $stmtVisite->bindParam(':id_adresse', $id_adresse);
            $stmtVisite->bindParam(':duree', $duree);
            $stmtVisite->bindParam(':titre', $titre);

            if ($stmtVisite->execute()) {
                echo "Visite insérée avec succès.";
                header('location: /pro');
            } else {
                echo "Erreur lors de l'insertion : " . implode(", ", $stmtVisite->errorInfo());
            }

            break;

        case 'spectacle':

            var_dump($capacite);

            $stmtSpectacle = $dbh->prepare("INSERT INTO sae_db._spectacle(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, capacite, duree) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :capacite, :duree)");
            $stmtSpectacle->bindParam(':id_offre', $id_offre);
            $stmtSpectacle->bindParam(':description', $description);
            $stmtSpectacle->bindParam(':resume', $resume);
            $stmtSpectacle->bindParam(':prix', $prixMin);
            $stmtSpectacle->bindParam(':date_creation', $dateCreation);
            $stmtSpectacle->bindParam(':id_adresse', $id_adresse);
            $stmtSpectacle->bindParam(':capacite', $capacite);
            $stmtSpectacle->bindParam(':duree', $duree);
            $stmtSpectacle->bindParam(':titre', $titre);

            if ($stmtSpectacle->execute()) {
                echo "Spectacle insérée avec succès.";
                header('location: /pro');
            } else {
                echo "Erreur lors de l insertion : " . implode(", ", $stmtSpectacle->errorInfo());
            }

            break;

        case 'parc_attraction':

            $stmtAttraction = $dbh->prepare("INSERT INTO sae_db._parc_attraction(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, nb_attractions, age_requis) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :nb_attraction, :age)");
            $stmtAttraction->bindParam(':id_offre', $id_offre);
            $stmtAttraction->bindParam(':description', $description);
            $stmtAttraction->bindParam(':resume', $resume);
            $stmtAttraction->bindParam(':prix', $prixMin);
            $stmtAttraction->bindParam(':date_creation', $dateCreation);
            $stmtAttraction->bindParam(':id_adresse', $id_adresse);
            $stmtAttraction->bindParam(':nb_attraction', $nb_attractions);
            $stmtAttraction->bindParam(':age', $age);
            $stmtAttraction->bindParam(':titre', $titre);

            if ($stmtAttraction->execute()) {
                echo "Parc d'attraction insérée avec succès.";
                header('location: /pro');
            } else {
                echo "Erreur lors de l'insertion : " . implode(", ", $stmtAttraction->errorInfo());
            }

            break;

        case 'restauration':

            $stmtRestauration = $dbh->prepare("INSERT INTO sae_db._restauration(id_offre, est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, id_pro, id_adresse, gamme_prix) VALUES (:id_offre, true, :description, :resume, :prix, :titre, :date_creation, null, null, null, :id_adresse, :gamme_prix)");
            $stmtRestauration->bindParam(':id_offre', $id_offre);
            $stmtRestauration->bindParam(':description', $description);
            $stmtRestauration->bindParam(':resume', $resume);
            $stmtRestauration->bindParam(':prix', $prixMin);
            $stmtRestauration->bindParam(':date_creation', $dateCreation);
            $stmtRestauration->bindParam(':id_adresse', $id_adresse);
            $stmtRestauration->bindParam(':gamme_prix', $gamme_prix);
            $stmtRestauration->bindParam(':titre', $titre);

            if ($stmtRestauration->execute()) {
                echo "Restauration insérée avec succès.";
                header('location: /pro');
            } else {
                echo "Erreur lors de l'insertion : " . implode(", ", $stmtRestauration->errorInfo());
            }

            break;

        default:
            echo "Veuillez sélectionner une activité.";
            exit;
    }

    // Insérer les prix dans la base de données
    require dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/tarif_public_controller.php';
    $tarifController = new TarifPublicController();
    foreach ($prices as $price) {
        if (!isset($price['name']) || !isset($price['value'])) {
            echo "Erreur : données de prix invalides.";
            continue;
        }

        $tarifController->createTarifPublic($price['name'], $price['value'], $id_offre);
    }

    if ($stmtAdresseOffre->execute()) {

        // Insérer les tarifs publics associés
        foreach ($prices as $price) {
            $dateCreation = date('Y-m-d H:i:s');
            // Gérer les différentes catégories d'offres

        }
    } else {
        echo "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresseOffre->errorInfo());
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
?>