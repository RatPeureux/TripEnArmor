<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/bdd.php';

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
    // *** Données standard
    $type_offre = $_POST["offre"];
    $titre = $_POST['titre'];
    $adresse = $_POST['user_input_autocomplete_address'];
    $code = $_POST['postal_code'];
    $ville = $_POST['locality'];
    $resume = $_POST['resume'];
    $description = $_POST['description'];
    $accessibilite = $_POST['accessibilite'];

    $activityType = $_POST['activityType'];
    // *** Données spécifiques
    $avec_guide = $_POST["guide"] ?? "on"; // VISITE
    $age = $_POST["age"];
    $dureeFormatted = sprintf('%02d:%02d:00', $_POST["hours"], $_POST["minutes"]); // ACTIVITE, VISITE, SPECTACLE
    $gamme_prix = $_POST['gamme_prix'];
    $capacite = $_POST['capacite'] ?? '';
    $langues = [
        "Français" => $_POST["langueFR"] ?? "on",
        "Anglais" => $_POST["langueEN"] ?? "on",
        "Espagnol" => $_POST["langueES"] ?? "on",
        "Allemand" => $_POST["langueDE"] ?? "on"
    ]; // VISITE
    $typesRepas = [
        "Petit déjeuner" => $_POST["repasPetitDejeuner"] ?? "on",
        "Brunch" => $_POST["repasBrunch"] ?? "on",
        "Déjeuner" => $_POST["repasDejeuner"] ?? "on",
        "Dîner" => $_POST["repasDiner"] ?? "on",
        "Boissons" => $_POST["repasBoissons"] ?? "on",
    ];
    $nb_attractions = (int) $_POST['nb_attractions'] ?? 0; // PARC_ATTRACTION
    $prices = $_POST['prices'] ?? [];
    $tags = $_POST['tags'][$activityType] ?? [];
    $id_pro = $_SESSION['id_pro'];
    $prestations = $_POST['newPrestationName'] ?? [];
    $horaires = $_POST['horaires'] ?? [];

    // Récupérer d'autres valeurs


    // *********************************************************************************************************************** Insertion
    /* Ordre de l'insertion :
    1. [x] Adresse
    3. [x] Image
    5. [x] Offre
    6. [x] Offre_Tag / Restauration_Tag
    7. [x] Offre_Image
    8. [x] Offre_Langue
    9. [x] TypeRepas 
    10. [x] Offre_Prestation
    11. Horaires
    12. [x] Tarif_Public
    */
    BDD::startTransaction();

    // Insérer l'adresse dans la base de données
    $realAdresse = extraireInfoAdresse($adresse);
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
    $adresseController = new AdresseController();
    $id_adresse = $adresseController->createAdresse($code, $ville, $realAdresse['numero'], $realAdresse['odonyme'], null);
    if (!$id_adresse) {
        echo "Erreur lors de la création de l'adresse.";
        BDD::rollbackTransaction();
        exit;
    }

    // Insérer l'offre dans la base de données
    $prixMin = calculerPrixMin($prices);
    $id_offre;
    switch ($activity) {
        case 'activite':
            // Insertion spécifique à l'activité
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/activite_controller.php';

            $activiteController = new ActiviteController();
            $id_offre = $activiteController->createActivite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $duree_formatted, $age, $prestations);

            if ($id_offre < 0) { // Cas d'erreur
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'visite':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/visite_controller.php';

            $visiteController = new VisiteController();
            $id_offre = $visiteController->createVisite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $avec_guide);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'spectacle':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/spectacle_controller.php';

            $spectacleController = new SpectacleController();
            $id_offre = $spectacleController->createSpectacle($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $capacite);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'parc_attraction':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/parc_attraction_controller.php';

            $parcAttractionController = new ParcAttractionController();
            $id_offre = $parcAttractionController->createParcAttraction($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'restauration':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';

            $restaurationController = new RestaurationController();
            $id_offre = $restaurationController->createRestauration($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix, $id_type_repas);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        default:
            echo "Aucune activité sélectionnée";
            BDD::rollbackTransaction();
            exit;
    }

    // Insérer les liens entre les offres et les tags dans la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_controller.php';
    $tagController = new TagController();
    if ($activityType === 'restauration') {
        // Insérer les tags de restauration
    } else {
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_offre_controller.php';
        $tagOffreController = new TagOffreController();

        foreach ($tags as $tag) {
            $tag_id = $tagController->getTagsByName($tag, 0);
            $tagOffreController->linkOffreAndTag($id_offre, $tagId);
        }
    }

    // Insérer les images dans la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
    $imageController = new ImageController();

    // *** CARTE
    if (!$imageController->uploadImage($id_offre, 'carte', $_FILES['photo-upload-carte']['tmp_name'], explode('/', $_FILES['photo-upload-carte']['type'])[1])) {
        echo "Erreur lors de l'upload de l'image de la carte.";
        BDD::rollbackTransaction();
        exit;

    }

    // *** DETAIL
    for ($i = 0; $i < count($_FILES['photo-detail']['name']); $i++) {
        if ($imageController->uploadImage($id_offre, 'detail', $_FILES['photo-detail']['tmp_name'][$i], explode('/', $_FILES['photo-detail']['type'][$i])[1])) {
            echo "Erreur lors de l'upload de l'image de détail.";
            BDD::rollbackTransaction();
            exit;
        }
    }

    if ($activity === 'parc_attraction') {
        if ($imageController->uploadImage($id_offre, 'plan', $_FILES['photo-plan']['tmp_name'], explode('/', $_FILES['photo-plan']['type'])[1])) {
            echo "Erreur lors de l'upload de l'image du plan.";
            BDD::rollbackTransaction();
            exit;
        }
    }

    if ($activityType === 'visite') {
        // Insérer les langues dans la base de données
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/langue_controller.php';
        $langueController = new LangueController();
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/visite_langue_controller.php';
        $visiteLangueController = new VisiteLangueController();

        foreach ($langues as $langue => $isIncluded) {
            if ($isIncluded) {
                $id_langue = $langueController->getInfosLangueByName($langue);
                $visiteLangueController->linkVisiteAndLangue($id_offre, $id_langue);
            }
        }
    } elseif ($activityType === 'restauration') {
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/type_repas_controller.php';
        $typeRepasController = new TypeRepasController();
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_type_repas_controller.php';
        $restaurationTypeRepasController = new RestaurationTypeRepasController();

        foreach ($typesRepas as $typeRepas => $isIncluded) {
            if ($isIncluded) {
                $id_type_repas = $typeRepasController->getTypeRepasByName($typeRepas);
                $restaurationTypeRepasController->linkRestaurantAndTypeRepas($id_offre, $id_type_repas);
            }
        }
    } elseif ($activityType === 'activite') {
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/prestation_manager.php';
        $prestationController = new PrestationController();
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/activite_prestation_controller.php';
        $activitePrestationController = new ActivitePrestationController();

        foreach ($prestations as $prestation => $isIncluded) {
            $id_prestation = $prestationController->getPrestationByName($prestation);
            if ($id_prestation < 0) {
                $id_prestation = $prestationController->createPrestation($prestation, $isIncluded);
            }

            $activitePrestationController->linkActiviteAndPrestation($id_offre, $id_prestation);
        }
    }

    // Insérer les horaires dans la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/horaire_controller.php';
    $horaireController = new HoraireController();

    foreach ($horaires as $jour) {
        $horaireController->createHoraire($jour['ouverture'], $jour['fermeture'], $jour['pause'], $jour['reprise'], $id_offre);
    }

    // Insérer les prix dans la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tarif_public_controller.php';
    $tarifController = new TarifPublicController();
    foreach ($prices as $price) {
        if (!isset($price['name']) || !isset($price['value'])) {
            echo "Erreur : données de prix invalides.";
            continue;
        }

        $tarifController->createTarifPublic($price['name'], $price['value'], $id_offre);
    }

    BDD::commitTransaction();
    header('location: /pro');
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
?>