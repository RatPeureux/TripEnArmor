<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../model/bdd.php';

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
    $langues = [$_POST["langueFR"] ?? "on" , $_POST["langueEN"] ?? "on", $_POST["langueES"] ?? "on", $_POST["langueDE"] ?? "on"]; // VISITE
    $nb_attractions = (int) $_POST['nb_attractions'] ?? 0; // PARC_ATTRACTION
    $prices = $_POST['prices'] ?? [];
    $tags = $_POST['tags'][$activityType] ?? [];
    $id_pro = $_SESSION['id_pro'];

    // Récupérer d'autres valeurs
    $prestations = $_POST['newPrestationName'] ?? [];


    // *********************************************************************************************************************** Insertion
    /* Ordre de l'insertion :
    1. [x] Adresse
    2. [x] Tag
    3. [x] Image
    5. [x] Offre
    6. [x] Offre_Tag
    7. [x] Offre_Image
    8. Offre_Langue
    9. Horaires
    10. [x] Tarif_Public
    11. Facture
    */
    BDD::startTransaction();

    // Insérer l'adresse dans la base de données
    $realAdresse = extraireInfoAdresse($adresse);
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/adresse_controller.php';
    $adresseController = new AdresseController();
    $id_adresse = $adresseController->createAdresse($code, $ville, $realAdresse['numero'], $realAdresse['odonyme'], null);
    if (!$id_adresse) {
        echo "Erreur lors de la création de l'adresse.";
        BDD::rollbackTransaction();
        exit;
    }

    // Insérer les tags dans la base de données --- INUTILE car ils choississent des tags déjà existants
    // require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/tag_controller.php';
    // $tagController = new TagController();
    // $tagIds = [];
    // foreach ($tags as $tag) {
    //     $tagIds[] = $tagController->createTag($tag);
    // }

    $prixMin = calculerPrixMin($prices);
    $id_offre;
    switch ($activity) {
        case 'activite':
            // Insertion spécifique à l'activité
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/activite_controller.php';

            $activiteController = new ActiviteController();
            $id_offre = $activiteController->createActivite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $duree_formatted, $age, $prestations);

            if ($id_offre < 0) { // Cas d'erreur
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'visite':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/visite_controller.php';

            $visiteController = new VisiteController();
            $id_offre = $visiteController->createVisite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $avec_guide);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'spectacle':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/spectacle_controller.php';

            $spectacleController = new SpectacleController();
            $id_offre = $spectacleController->createSpectacle($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $capacite);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'parc_attraction':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/parc_attraction_controller.php';

            $parcAttractionController = new ParcAttractionController();
            $id_offre = $parcAttractionController->createParcAttraction($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age);

            if ($id_offre < 0) {
                echo "Erreur lors de l'insertion : " . $id_offre;
                BDD::rollbackTransaction();
            }
            break;

        case 'restauration':

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/restauration_controller.php';

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
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/tag_offre_controller.php';
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/tag_controller.php';
    $tagController = new TagController();
    $tagOffreController = new TagOffreController();

    foreach ($tags as $tag) {
        $tagId = $tagController->getTagsByName($tag, 0);
        $tagOffreController->linkOffreAndTag($id_offre, $tagId);
    }

    // Insérer les image dans la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/image_controller.php';
    $uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/../public/images/';

    $imageController = new ImageController();
    $imageIds = [];

    // CARTE
    $uploadName = $uploadDir . "carte/" . $id_offre . '0.' . explode('/', $_FILES['photo-upload-carte']['type'])[1];
    if (!move_uploaded_file($_FILES['photo-upload-carte']['tmp_name'], $uploadName)) {
        echo "Erreur lors de l'upload de l'image de la carte.";
        BDD::rollbackTransaction();
        exit;
    }
    $imagesIds['carte'] = $imageController->createImage($uploadName);

    // DETAIL
    for ($i = 0; $i < count($_FILES['photo-detail']['name']); $i++) {
        $uploadName = $uploadDir . "detail/" . $id_offre . '-' . ($i + 1) . '.' . explode('/', $_FILES['photo-detail']['type'][$i])[1];

        if (!move_uploaded_file($_FILES['photo-detail']['tmp_name'][$i], $uploadName)) {
            echo "Erreur lors de l'upload de l'image de détail.";
            BDD::rollbackTransaction();
            exit;
        }

        $imageIds['detail'][] = $imageController->createImage($uploadName);
    }

    if ($activity === 'parc_attraction') {
        $uploadName = $uploadDir . "plan/" . $id_offre . implode('/', $_FILES['photo-plan']['type'])[1];
        if (!move_uploaded_file($_FILES['photo-plan']['tmp_name'], $uploadName)) {
            echo "Erreur lors de l'upload de l'image du plan.";
            BDD::rollbackTransaction();
            exit;
        }
        $imagesIds["plan"][] = $imageController->createImage($uploadName);
    }

    // Insérer les prix dans la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '../controller/tarif_public_controller.php';
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

    BDD::commitTransaction();
    header('location: /pro');
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
?>