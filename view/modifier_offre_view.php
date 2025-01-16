<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/modifier_offre_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/type_offre_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/cat_offre_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_resto_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/horaire_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/type_repas_restaurant_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_offre_controller.php';
$pro = verifyPro();


// Récupération de l'ID de l'offre
$id_offre = $_GET['id_offre'];
echo "ID de l'offre : " . $id_offre . "<br>";
if (!$id_offre) {
	die('Erreur : ID de l\'offre manquant.');
}

// Récupération des informations de l'offre
$modifier_offre_controller = new ModifierOffreController();
$offre = $modifier_offre_controller->getOffreById($_GET['id_offre']);
if (!$offre) {
	die('Erreur : Offre introuvable.');
}


// Récupération des informations annexes (debug)
$typeOffreController = new TypeOffreController();
$typesOffres = $typeOffreController->getInfosTypeOffre($offre['id_type_offre']);


$catOffreController = new CatOffreController();
$catOffre = $catOffreController->getOffreCategorie($offre['id_offre']);

$tagRestoController = new TagRestoController();
$tagsResto = $tagRestoController->getTagResto($offre['id_offre']);
foreach ($tagsResto as $tag) {
}

$horaireController = new HoraireController();
$horaires = $horaireController->getHorairesOfOffre($offre['id_offre']);

$typeRepasRestaurantController = new TypeRepasRestaurantController();
$typesRepasRestaurant = $typeRepasRestaurantController->getTypeRepasRestaurant($offre['id_offre']);
$repasNoms = array_map(function ($repas) {
	return $repas['nom'];
}, $typesRepasRestaurant);


$imagesController = new ImageController();
$images = $imagesController->getImagesOfOffre($offre['id_offre']);

$tagOffreController = new TagOffreController();
$tagsOffre = $tagOffreController->getTagsByIdOffre($offre['id_offre']);

$adresseController = new AdresseController();
$adresse = $adresseController->getInfosAdresse($offre['id_adresse']);
$odonyme = $_POST['user_input_autocomplete_address'] ?? $adresse['odonyme'];
if (!$adresse) {
	echo "ERREUR : Adresse introuvable.";
	return -1;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Récupération des données du formulaire pour l'offre
	$titre = $_POST['titre'] ?? $offre['titre'];
	$description = $_POST['description'] ?? $offre['description'];
	$resume = $_POST['resume'] ?? $offre['resume'];
	$prix_mini = $_POST['prix_mini'] ?? $offre['prix_mini'];
	$date_suppression = $_POST['date_suppression'] ?? $offre['date_suppression'];
	$est_en_ligne = isset($_POST['est_en_ligne']) ? 1 : 0;
	$id_type_offre = $_POST['id_type_offre'] ?? $offre['id_type_offre'];
	$id_pro = $_SESSION['id_pro'] ?? $offre['id_pro'];
	$id_adresse = $_POST['id_adresse'] ?? $offre['id_adresse'];
	$option = $_POST['option'] ?? $offre['option'];
	$accessibilite = $_POST['accessibilite'] ?? $offre['accessibilite'];
	$date_mise_a_jour = date('Y-m-d H:i:s');

	// Récupération des données de l'adresse
	$code_postal = $_POST['postal_code'] ?? $adresse['code_postal'];
	$ville = $_POST['locality'] ?? $adresse['ville'];
	$numero = $_POST['numero'] ?? $adresse['numero'];
	$odonyme = $_POST['user_input_autocomplete_address'] ?? $adresse['odonyme'];
	$complement = $_POST['complement'] ?? $adresse['complement'];
	$typesRepas = [
		"Petit déjeuner" => $_POST["repasPetitDejeuner"] ?? "off",
		"Brunch" => $_POST["repasBrunch"] ?? "off",
		"Déjeuner" => $_POST["repasDejeuner"] ?? "off",
		"Dîner" => $_POST["repasDiner"] ?? "off",
		"Boissons" => $_POST["repasBoissons"] ?? "off",
	];




    	// Mise à jour des informations de l'offre
    	$modifier_offre_controller->updateOffre(
        	$id_offre, $titre, $description, $resume, $prix_mini, $offre['date_creation'],
        	$date_mise_a_jour, $date_suppression, $est_en_ligne, $id_type_offre,
        	$id_pro, $id_adresse, $accessibilite
    	);

    	// Mise à jour de l'adresse
    	if (!empty($id_adresse)) {
        	$adresseController->updateAdresse($offre['id_adresse'], $code_postal, $ville, $numero, $odonyme, $complement);
        	
    	} else {
        	throw new Exception('L\'identifiant de l\'adresse est manquant');
    	}

		if (!empty($_FILES['photo-upload-carte']['tmp_name'])) {
			if (!$imagesController->uploadImage($id_offre, 'carte', $_FILES['photo-upload-carte']['tmp_name'], explode('/', $_FILES['photo-upload-carte']['type'])[1])) {
				echo "Erreur lors de l'upload de l'image de la carte.";
				BDD::rollbackTransaction();
				exit;
			}
		}

		if (!empty($_FILES['photo-detail']['tmp_name'][0])) {
			foreach ($_FILES['photo-detail']['tmp_name'] as $index => $tmpName) {
				if (!$imagesController->uploadImage($id_offre, 'detail', $tmpName, explode('/', $_FILES['photo-detail']['type'][$index])[1])) {
					echo "Erreur lors de l'upload de l'image de détail.";
					BDD::rollbackTransaction();
					exit;
				}
			}
		}

		require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/type_repas_restaurant_controller.php';


		// Instanciation du contrôleur
		$query = "SELECT * FROM sae_db.vue_restaurant_type_repas WHERE id_offre = ?";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(1, $id_offre);
		$stmt->execute();
		$typesRepas2 = $stmt->fetchAll(PDO::FETCH_ASSOC);


		// Vérifiez que l'offre est de type restauration et que l'ID de l'offre est défini
		// L'ID de l'offre et les types de repas sélectionnés

		if ($catOffre[0]['type_offre'] === 'restauration' && isset($id_offre)) {
			// Vérifier si des types de repas sont envoyés
			if (!empty($typesRepas)) {
				echo "Restauration<br>";

				// Appel au contrôleur pour ajouter les types de repas associés à l'offre
				foreach ($typesRepas as $nom_type_repas => $estActive) {
					if($estActive == 'on'){
						$query = "DELETE FROM sae_db.vue_restaurant_type_repas WHERE id_offre = ? AND nom = ?";
						$stmt = $dbh->prepare($query);
						$stmt->bindParam(1, $id_offre);
						$stmt->bindParam(2, $nom_type_repas);
						if ($stmt->execute()) {
							echo "Type de repas supprimé.<br>";
						} else {
							echo "Erreur lors de la suppression du type de repas.";
						}
						$query = "INSERT INTO sae_db.vue_restaurant_type_repas (id_offre, nom) VALUES (?, ?)";
						$stmt = $dbh->prepare($query);
						$stmt->bindParam(1, $id_offre);
						$stmt->bindParam(2, $nom_type_repas);
						if ($stmt->execute()) {
							echo "Type de repas inséré.<br>";
						} else {
							echo "Erreur lors de l'insertion du type de repas.";
						}
					}
					

				}
			} else {
				echo "Aucun type de repas sélectionné.";
			}
		}

		

		header('Location: /pro');
		exit;
	
}



// Vérification de l'utilisateur connecté
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
	<script type="module" src="/scripts/main.js" defer></script>
	<script type="text/javascript"
    	src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCzthw-y9_JgvN-ZwEtbzcYShDBb0YXwA8&language=fr"></script>
	<script type="text/javascript" src="/scripts/autocomplete.js"></script>

	<title>Création d'offre - Professionnel - PACT</title>
</head>

<body>
	<?php
	// Partie pour traiter la soumission du formulaire
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/bdd.php';
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
        	// Utiliser une expression régulière pour extraire le numéro et l'odonyme
        	if (preg_match('/^(\d+)\s+(.*)$/', $adresse, $matches)) {
            	return [
                	'numero' => $matches[1],
                	'odonyme' => $matches[2],
            	];
        	}

        	// Si l'adresse ne correspond pas au format attendu, retourner des valeurs par défaut
        	return [
            	'numero' => '',
            	'odonyme' => $adresse,
        	];
    	}
    	// ******************************************************************************************************************** Récupération des données du POST
    	// Récupération des données du formulaire
    	// *** Données standard
    	$id_type_offre = $_POST["type_offre"];
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
    	$duree_formatted = sprintf('%02d:%02d:00', $_POST["hours"], $_POST["minutes"]); // ACTIVITE, VISITE, SPECTACLE
    	$gamme_prix = $_POST['gamme2prix'];
    	$capacite = $_POST['capacite'] ?? '';
    	$langues = [
        	"Français" => $_POST["langueFR"] ?? "on",
        	"Anglais" => $_POST["langueEN"] ?? "on",
        	"Espagnol" => $_POST["langueES"] ?? "on",
        	"Allemand" => $_POST["langueDE"] ?? "on"
    	]; // VISITE
    	$nb_attractions = (int) $_POST['nb_attractions'] ?? 0; // PARC_ATTRACTION
    	$prices = $_POST['prices'] ?? [];
    	$tags = $_POST['tags'][$activityType] ?? [];
    	$id_pro = $_SESSION['id_pro'];
    	$prestations = $_POST['newPrestationName'] ?? [];
    	$horaires = $_POST['horaires'] ?? [];
    	$option = $_POST['option'] ?? [];
    	$duree_option = $_POST['duration'] ?? [];
    	$debut_option = $_POST['start_date'] ?? [];



    	// echo "Option : " . $option . "<br>";
    	// echo "Durée option : " . $duree_option . "<br>";
    	// echo "Debut option : " . $debut_option . "<br>";
    
    	// Récupérer d'autres valeurs
    

    	// *********************************************************************************************************************** Insertion
    	// Ordre de l'insertion :
    	// 	1. [x] Adresse
    	// 	3. [x] Image
    	// 	5. [x] Offre
    	// 	6. [x] Offre_Tag / Restauration_Tag
    	// 	7. [x] Offre_Image
    	// 	8. [x] Offre_Langue
    	// 	9. [x] TypeRepas
    	// 	10. [x] Offre_Prestation
    	// 	11. Horaires
    	// 	12. [x] Tarif_Public
    

    	BDD::startTransaction();
    	try {
        	// Insérer l'adresse dans la base de données
        	$realAdresse = extraireInfoAdresse($adresse);
        	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
        	$adresseController = new AdresseController();
        	$id_adresse = $adresseController->createAdresse($code, $ville, $realAdresse['numero'], $realAdresse['odonyme'], null);
        	if (!$id_adresse) {
            	// echo "Erreur lors de la création de l'adresse.";
            	BDD::rollbackTransaction();
            	exit;
        	}
        	// echo"Adresse insérée.<br>";
    
        	// Insérer l'offre dans la base de données
        	$prixMin = calculerPrixMin($prices);
        	$id_offre;
        	switch ($activityType) {
            	case 'activite':
                	// Insertion spécifique à l'activité
                	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/activite_controller.php';
                	$activiteController = new ActiviteController();
                	$id_offre = $activiteController->createActivite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $duree_formatted, $age, $prestations);

                	if ($id_offre < 0) { // Cas d'erreur
                    	// echo "Erreur lors de l'insertion : " . $id_offre;
                    	BDD::rollbackTransaction();
                    	exit;
                	}
                	// echo "Activité insérée.<br>";
                	break;

            	case 'visite':
                	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/visite_controller.php';

                	$visiteController = new VisiteController();
                	$id_offre = $visiteController->createVisite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $avec_guide);

                	if ($id_offre < 0) {
                    	// echo "Erreur lors de l'insertion : " . $id_offre;
                    	BDD::rollbackTransaction();
                    	exit;
                	}
                	// echo "Visite insérée<br>";
                	break;

            	case 'spectacle':

                	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/spectacle_controller.php';

                	$spectacleController = new SpectacleController();
                	$id_offre = $spectacleController->createSpectacle($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $capacite, $dureeFormatted);

                	if ($id_offre < 0) {
                    	// echo "Erreur lors de l'insertion : " . $id_offre;
                    	BDD::rollbackTransaction();
                    	exit;
                	}
                	// echo "Spectacle inséré<br>";
                	break;

            	case 'parc_attraction':

                	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/parc_attraction_controller.php';

                	$parcAttractionController = new ParcAttractionController();
                	$id_offre = $parcAttractionController->createParcAttraction($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age);

                	if ($id_offre < 0) {
                    	// echo "Erreur lors de l'insertion : " . $id_offre;
                    	BDD::rollbackTransaction();
                    	exit;
                	}
                	// echo "Parc d'attraction inséré<br>";
                	break;

            	case 'restauration':

                	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';

                	$restaurationController = new RestaurationController();
                	$id_offre = $restaurationController->createRestauration($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix);

                	if ($id_offre < 0) {
                    	// echo "Erreur lors de l'insertion : " . $id_offre;
                    	BDD::rollbackTransaction();
                    	exit;
                	}
                	// echo "Restauration insérée<br>";
                	break;

            	default:
                	// echo "Aucune activité sélectionnée";
                	BDD::rollbackTransaction();
                	exit;
        	}
        	// echo"new id_offre : " . $id_offre . "<br>";
    
        	// Insérer les liens entre les offres et les tags dans la base de données
        	if ($activityType === 'restauration') {
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_restaurant_controller.php';
            	$tagRestaurationController = new TagRestaurantController();
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_restaurant_restauration_controller.php';
            	$tagRestaurationRestaurantController = new TagRestaurantRestaurationController();

            	foreach ($tags as $tag) {
                	$tags_id = $tagRestaurationController->getTagsRestaurantByName($tag);

                	$tag_id = $tags_id ? $tags_id[0]['id_tag_restaurant'] : $tagRestaurationController->createTag($tag);

                	$tagRestaurationRestaurantController->linkRestaurationAndTag($id_offre, $tag_id);
            	}
            	// echo "Tags Restaurant inséré<br>";
        	} else {
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_controller.php';
            	$tagController = new TagController();
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_offre_controller.php';
            	$tagOffreController = new TagOffreController();

            	foreach ($tags as $tag) {
                	$tags_id = $tagController->getTagsByName($tag);
                	$tag_id = $tags_id ? $tags_id[0]['id_tag'] : $tagController->createTag($tag);
                	$tagOffreController->linkOffreAndTag($id_offre, $tag_id);
            	}
            	// echo "Tags insérés.<br>";
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
        	// echo"Image de la carte insérée.<br>";
    
        	// *** DETAIL
        	if ($_FILES['photo-detail']['error'][0] !== 4) {
            	for ($i = 0; $i < count($_FILES['photo-detail']['name']); $i++) {
                	if (!$imageController->uploadImage($id_offre, 'detail-' . $i, $_FILES['photo-detail']['tmp_name'][$i], explode('/', $_FILES['photo-detail']['type'][$i])[1])) {
                    	// echo "Erreur lors de l'upload de l'image de détail.";
                    	BDD::rollbackTransaction();
                    	exit;
                	}
            	}
            	// echo "Images de détail insérées.<br>";
        	}

        	//$existingImages = $imageController->getImagesOfOffre($offre['id_offre']);
        	foreach ($images as $image) {
            	if (strpos($image['type'], 'detail-') !== false) {
                	echo "Image de détail existante : " . $image['url'] . "<br>";
            	} else if ($image['type'] === 'carte') {
                	echo "Image de la carte existante : " . $image['url'] . "<br>";
                	echo "<script>
                    	document.getElementById('preview-image').src = '{$image['url']}';
                    	document.getElementById('photo-upload-carte').required = false;
                	</script>";
            	}
        	}

        	if ($activityType === 'parc_attraction') {
            	if (!$imageController->uploadImage($id_offre, 'plan', $_FILES['photo-plan']['tmp_name'], explode('/', $_FILES['photo-plan']['type'])[1])) {
                	echo "Erreur lors de l'upload de l'image du plan.";
                	BDD::rollbackTransaction();
                	exit;
            	}
            	// echo "Image du plan insérée.<br>";
        	}

        	if ($activityType === 'visite' && $avec_guide) {
            	// Insérer les langues dans la base de données
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/langue_controller.php';
            	$langueController = new LangueController();
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/visite_langue_controller.php';
            	$visiteLangueController = new VisiteLangueController();

            	for ($i = 1; $i < count($langueController->getInfosAllLangues()) + 1; $i++) { // foreach ($langues as $langue => $isIncluded) {
                	$isIncluded = $_POST['langue' . $i] ?? "on";
                	if ($isIncluded) {
                    	// echo "Langue incluse : " . $langueController->getInfosLangue($i)['nom'] . "<br>";
                    	$visiteLangueController->linkVisiteAndLangue($id_offre, $i);
                	}
            	}
            	// echo "Langues insérées.<br>";
        	} elseif ($activityType === 'restauration') {
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/type_repas_controller.php';
            	$typeRepasController = new TypeRepasController();
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_type_repas_controller.php';
            	$restaurationTypeRepasController = new RestaurationTypeRepasController();

            	foreach ($typesRepas as $typeRepas => $isIncluded) {
                	if ($isIncluded) {
                    	$query = $typeRepasController->getTypeRepasByName($typeRepas);

                    	$id_type_repas = $query ? $query[0]['id_type_repas'] : $typeRepasController->createTypeRepas($typeRepas);

                    	$restaurationTypeRepasController->linkRestaurantAndTypeRepas($id_offre, $id_type_repas);
                	}
            	}
            	// echo "Types de repas insérés.<br>";
        	} elseif ($activityType === 'activite') {
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/prestation_controller.php';
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
            	// echo "Prestations insérées.<br>";
        	}

        	// Insérer les horaires dans la base de données
        	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/horaire_controller.php';
        	$horaireController = new HoraireController();

        	foreach ($horaires as $key => $jour) {
            	$horaireController->createHoraire($key, $jour['ouverture'], $jour['fermeture'], $jour['pause'], $jour['reprise'], $id_offre);
        	}
        	// echo"Horaires insérés.<br>";
    
        	// Insérer les prix dans la base de données
        	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tarif_public_controller.php';
        	$tarifController = new TarifPublicController();
        	foreach ($prices as $price) {
            	if (!isset($price['name']) || !isset($price['value'])) {
                	// echo "Erreur : données de prix invalides.";
                	continue;
            	}

            	$tarifController->createTarifPublic($price['name'], $price['value'], $id_offre);
        	}
        	// echo"Prix insérés.<br>";
        	BDD::commitTransaction();

        	// Insérer les options dans la base de données
        	if ($option == "A la une" || $option == "En relief") {
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
            	$stmt = $dbh->prepare("INSERT INTO sae_db._souscription (nb_semaines, date_lancement) VALUES (:nb_semaines, :date_lancement) RETURNING id_souscription");
            	$stmt->bindParam(':nb_semaines', $duree_option);
            	$stmt->bindParam(':date_lancement', $debut_option);
            	$stmt->execute();

            	$id_souscription = $stmt->fetch(PDO::FETCH_ASSOC)['id_souscription'];

            	$stmt = $dbh->prepare("INSERT INTO sae_db._offre_souscription_option (id_offre, id_souscription, nom_option) VALUES (:id_offre, :id_souscription, :nom_option)");
            	$stmt->bindParam(':id_offre', $id_offre);
            	$stmt->bindParam(':id_souscription', $id_souscription);
            	$stmt->bindParam(':nom_option', $option);

            	$stmt->execute();
        	}

        	header('location: /pro');
    	} catch (Exception $e) {
        	echo "Erreur lors de l'insertion : " . $e->getMessage();
        	BDD::rollbackTransaction();
        	exit;
    	}
	} else {
    	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/type_offre_controller.php';

    	$typeOffreController = new TypeOffreController();
    	$typesOffre = $typeOffreController->getAllTypeOffre();
    	array_multisort($typesOffre, SORT_DESC);
    	?>
                        	<!-- Conteneur principal pour le contenu -->
                        	<div class="flex flex-col w-full justify-between items-center align-baseline min-h-screen">

        	<div id="menu-pro">
            	<?php
            	$pagination = 2;
            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/menu-pro.php';
            	?>
        	</div>

        	<div class="w-full">
            	<!-- Inclusion du header -->
            	<?php
            	include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header-pro.php';
            	?>
        	</div>

                            	<div class="grow w-full max-w-[1280px] mt-20 flex flex-col items-center justify-center p-2 ">
                                	<!-- Lien de retour avec une icône et un titre -->
                                	<div class="w-full flex">
                                    	<h1 class="text-h1">Création d'offre</h1>
                                	</div>
                                	<!-- Section de sélection de l'offre -->
                                	<form id="formulaire" action="" method="POST" class="grow block w-full space-y-8"
                                    	enctype="multipart/form-data">
                                    	<div class="
                	<?php if ($pro['data']['type'] === 'prive') {
                    	echo "grid grid-cols-2";
                	} ?>
                	justify-around items-evenly gap-6 w-full md:space-y-0 md:flex-nowrap">
                                        	<!-- Carte de l'offre gratuite -->
                                        	<?php
                                        	foreach ($typesOffre as $i => $typeOffre) {
                                            	$cardColor = $i % 2 == 0 ? 'secondary' : 'primary';
                                            	$cardVisible = $pro['data']['type'] == 'prive' ? ($typeOffre['id_type_offre'] == 1 ? 'hidden' : '') : ($typeOffre['id_type_offre'] == 1 ? '' : 'hidden');
                                            	$subTitle = "Pour les entreprises et organismes privés";
                                            	$avantages = [
                                                	"Jusqu’à 10 photos de présentations",
                                                	"Réponse aux avis des membres"
                                            	];

                                            	if ($typeOffre['id_type_offre'] == 1) { // Gratuit
                                                	$subTitle = "Pour les associations et les organismes publics";
                                            	} else if ($typeOffre['id_type_offre'] == 2) { // Premium
                                                	$avantages[] = "Possibilité de remplir une grille tarifaire";
                                                	$avantages[] = "Possibilité de souscrire aux options “À la une” et “En relief”";
                                                	$avantages[] = "<span class='font-bold'>Mise sur liste noire de 3 commentaires<span>";
                                            	} else if ($typeOffre['id_type_offre'] == 3) { // Standard
                                                	$avantages[] = "<span class='font-bold'>Possibilité de remplir une grille tarifaire<span>";
                                                	$avantages[] = "<span class='font-bold'>Possibilité de souscrire aux options “À la une” et “En relief”<span>";
                                            	}
                                            	?>
                                                                	<style>
                                                                    	<?php if ($typesOffres['nom'] != $typeOffre['nom']) { ?>
                                                                                            	.type_offre_

                                                                                            	<?php echo $typeOffre['id_type_offre']; ?>
                                                                                            	+label {
                                                                                                	opacity: 1;
                                                                                            	}

                                                                    	<?php } ?>
                                                                	</style>
                                                                	<div
                                                                    	class="border border-<?php echo $cardColor; ?>  flex-col justify-center w-full text-<?php echo $cardColor; ?> p-4 has-[:checked]:bg-<?php echo $cardColor; ?> has-[:checked]:text-white md:h-full <?php echo $cardVisible; ?>">
                                                                    	<input type="radio" name="type_offre" id="type_offre_<?php echo $typeOffre['id_type_offre']; ?>"
                                                                        	value="<?php echo $typeOffre['id_type_offre']; ?>" class="hidden" <?php echo ($typesOffres['nom'] == $typeOffre['nom']) ? 'checked disabled' : 'disabled'; ?>>
                                                                    	<label for="type_offre_<?php echo $typeOffre['id_type_offre']; ?>"
                                                                        	class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                                                                        	<div class="h-full divide-y divide-current">
                                                                            	<div>
                                                                                	<h1 class="text-h1 leading-none mt-1 text-center">
                                                                                    	<?php echo ucfirst($typeOffre['nom']) ?>
                                                                                	</h1>
                                                                                	<h1 class="text-center font-bold">
                                                                                    	<?php echo $subTitle ?>
                                                                                	</h1>
                                                                            	</div>
                                                                            	<div>
                                                                                	<div class="ml-8">
                                                                                    	<ul class="list-disc text-left text-small my-2">
                                                                                        	<?php
                                                                                        	foreach ($avantages as $avantage) {
                                                                                            	echo "<li>$avantage</li>";
                                                                                        	}
                                                                                        	?>
                                                                                    	</ul>
                                                                                	</div>
                                                                            	</div>
                                                                        	</div>
                                                                        	<div>
                                                                            	<h1 class="text-h1 leading-none mt-1 text-center py-2">
                                                                                	<?php
                                                                                	if ($typeOffre["prix_ht"] == 0) {
                                                                                    	echo "0€/jour en ligne";
                                                                                	} else { ?>
                                                                                                        	HT <?php echo $typeOffre['prix_ht']; ?>€/jour en ligne<br>
                                                                                                        	<span class="text-h2">
                                                                                                            	TTC <?php echo $typeOffre['prix_ttc']; ?>€/jour en ligne
                                                                                                        	</span>
                                                                                	<?php } ?>
                                                                            	</h1>
                                                                        	</div>
                                                                    	</label>
                                                                	</div>
                                                                	<?php
                                        	}
                                        	?>

                                        	<!-- <div class="border border-secondary  flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white md:h-full <?php if ($pro['data']['type'] === "prive") {
                                            	echo "hidden";
                                        	} ?>">
                        	<input type="radio" name="type_offre" id="type_offre_1" value="1" class="hidden">
                        	<label for="type_offre_1"
                            	class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                            	<div class="h-full divide-y divide-current">
                                	<div>
                                    	<h1 class="text-h1 leading-none mt-1 text-center">Gratuite</h1>
                                    	<h1 class="text-center font-bold">Pour les associations et les organismes publics
                                    	</h1>
                                	</div>
                                	<div>
                                    	<div class="ml-8">
                                        	<ul class="list-disc text-left text-small my-2">
                                            	<li>Jusqu’à 10 photos de présentations</li>
                                            	<li>Réponse aux avis des membres</li>
                                        	</ul>
                                    	</div>
                                	</div>
                            	</div>
                            	<div>
                                	<h1 class="text-h1 leading-none mt-1 text-center py-2">0€/mois</h1>
                            	</div>
                        	</label>
                    	</div>
                    	<div class="border border-primary  flex-col justify-center w-full text-primary p-4 has-[:checked]:bg-primary has-[:checked]:text-white md:h-full <?php if ($pro['data']['type'] === "public") {
                        	echo "hidden";
                    	} ?>">
                        	<input type="radio" name="type_offre" id="type_offre_2" value="2" class="hidden">
                        	<label for="type_offre_2"
                            	class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                            	<div class="h-full divide-y divide-current">
                                	<div>
                                    	<h1 class="text-h1 leading-none mt-1 text-center">Standard</h1>
                                    	<h1 class="text-center font-bold">Pour les entreprises et organismes privés</h1>
                                	</div>
                                	<div class="h-full">
                                    	<div class="ml-8">
                                        	<ul class="list-disc text-left text-small my-2">
                                            	<li>Jusqu’à 10 photos de présentations</li>
                                            	<li>Réponse aux avis des membres</li>
                                            	<li>Possibilité de souscrire aux options “À la une” et “En relief”</li>
                                        	</ul>
                                    	</div>
                                	</div>
                            	</div>
                            	<div>
                                	<h1 class="text-h1 leading-none mt-1 text-center py-2">12€/mois</h1>
                            	</div>
                        	</label>
                    	</div>
                    	<div class="border border-secondary  flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white md:h-full <?php if ($pro['data']['type'] === "public") {
                        	echo "hidden";
                    	} ?>">
                        	<input type="radio" name="type_offre" id="type_offre_3" value="3" class="hidden">
                        	<label for="type_offre_3"
                            	class="divide-y divide-current cursor-pointer flex flex-col justify-between h-full">
                            	<div class="h-full divide-y divide-current">
                                	<div>
                                    	<h1 class="text-h1 leading-none mt-1 text-center">Premium</h1>
                                    	<h2 class="text-center font-bold">Pour les entreprises et organismes privés</h2>
                                	</div>
                                	<div class="h-full">
                                    	<p class="mt-2 text-small font-bold">Standard +</p>
                                    	<div class="ml-8">
                                        	<ul class="list-disc text-left text-small">
                                            	<li>Mise sur liste noire de 3 commentaires</li>
                                        	</ul>
                                    	</div>
                                	</div>
                            	</div>
                            	<div>
                                	<p class="text-h1 leading-none mt-1 text-center py-2">19€/mois</p>
                            	</div>
                        	</label>
                    	</div> -->
                                    	</div>
                                    	<div class="w-full flex space-x-12">
                                        	<div class="w-full">
                                            	<div class="w-full flex flex-col justify-center items-center space-y-4 part1 hidden">
                                                	<h2 class="w-full text-h2 text-secondary">Informations</h2>

                                                	<!-- Titre -->
                                                	<div class="flex flex-col justify-center w-full">
                                                    	<label for="titre" class="text-nowrap">Titre : </label>
                                                    	<input type="text" id="titre"
                                                        	class="border border-secondary p-2 bg-white w-full"
                                                        	name="titre"
                                                        	value="<?php echo htmlspecialchars($offre['titre']); ?>"
                                                        	required>
                                                	</div>

                                                	<!-- Auteur -->
                                                	<div class="flex flex-col w-full">
                                                    	<label for="auteur" class="text-nowrap">Auteur :</label>
                                                    	<p id="auteur"
                                                        	class="border border-secondary  p-2 bg-gray-200 w-full text-gray-600">
                                                        	<?php
                                                        	if ($pro) {
                                                            	echo $pro['nom_pro'];
                                                        	} else {
                                                            	echo "Nom du compte";
                                                        	} ?>
                                                    	</p>
                                                	</div>

                                                	<!-- Adresse -->
                                                	<div class="justify-between items-center w-full mb-2">
                                                    	<label for="user_input_autocomplete_address" class="text-nowrap">Adresse :</label>
                                                    	<input type="text" id="user_input_autocomplete_address"
                                                        	name="user_input_autocomplete_address"
                                                        	value="<?php echo $adresse['numero'] . ' ' . $adresse['odonyme']; ?>"
                                                        	class="border border-secondary  p-2 bg-white w-full" required>
                                                	</div>

                                                	<div class="justify-between items-center w-full">
                                                    	<label for="locality" class="text-nowrap">Ville :</label>
                                                    	<input id="locality" name="locality" type="text"
                                                        	pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$"
                                                        	title="Saisir votre ville" value="<?php print_r($adresse['ville']); ?>"
                                                        	class="border border-secondary  p-2 bg-white w-full" required>

                                                    	<label for="postal_code" class="text-nowrap">Code postal :</label>
                                                    	<input id="postal_code" name="postal_code" type="number"
                                                        	pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Format : 12345"
                                                        	value="<?php print_r($adresse['code_postal']); ?>"
                                                        	class="border border-secondary  p-2 bg-white w-24 w-full" required>
                                                	</div>

                                                	<div class="w-full justify-between">
                                                    	<!-- Photo principale -->
                                                    	<div class="flex flex-col justify-between w-full">
                                                        	<label for="photo-upload-carte" class="text-nowrap w-full">Changer la photo de la carte :</label>
                                                        	<input value="<?php var_dump($images['carte'])?>" type="file" name="photo-upload-carte" id="photo-upload-carte"
                                                        	class="text-center text-secondary block w-full
                                                        	border-dashed border-2 border-secondary rounded-lg p-2
                                                        	file:mr-5 file:py-3 file:px-10
                                                        	file:text-small file:font-bold  file:text-secondary
                                                        	file:border file:border-secondary
                                                        	hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" accept=".svg,.png,.jpg,.jpeg,.webp"/>

                                                    	</div>
                                                    	<!-- Photos détaillée -->
                                                    	<div class="flex flex-col justify-between w-full">
                                                        	<label for="photo-detail[]" class="text-nowrap w-full">Changer les photos de l'offre détaillée:</label>
                                                        	<input type="file" name="photo-detail[]" id="photo-detail[]" class="text-center
                                                        	text-secondary block w-full
                                                        	border-dashed border-2 border-secondary rounded-lg p-2
                                                        	file:mr-5 file:py-3 file:px-10
                                                        	file:
                                                        	file:text-small file:font-bold  file:text-secondary
                                                        	file:border file:border-secondary
                                                        	hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" accept=".svg,.png,.jpg,.jpeg,.webp" multiple />
                                                    	</div>
                                                	</div>

                                                	<!-- Résumé -->
                                                	<div class="flex flex-col items-center w-full max-w-full">
                                                    	<label for="resume" class="text-nowrap w-full">Résumé :</label>
                                                    	<textarea id="resume" name="resume"
                                                        	class="border border-secondary  p-2 bg-white w-full" rows="4"
                                                        	required> <?php print_r($offre['resume']); ?></textarea>
                                                	</div>

                                                	<!-- Description -->
                                                	<div class="flex flex-col items-center w-full">
                                                    	<label for="description" class="text-nowrap w-full">Description :</label>
                                                    	<textarea id="description" name="description"
                                                        	class="border border-secondary  p-2 bg-white w-full" rows="11"
                                                        	required><?php print_r($offre['description']); ?></textarea>
                                                	</div>

                                                	<!-- Accessibilité -->
                                                	<div class="flex flex-col justify-between items-center w-full">
                                                    	<label for="accessibilite" class="text-nowrap w-full">Accessibilité :</label>
                                                    	<textarea id="accessibilite" name="accessibilite"
                                                        	class="border border-secondary  p-2 bg-white w-full"
                                                        	rows="5"><?php print_r($offre['accessibilite']); ?></textarea>
                                                	</div>
                                            	</div>
                                            	<div class="w-full flex flex-col justify-center items-center space-y-4 part2 hidden">
                                                	<h2 class="w-full text-h2 text-secondary">Informations supplémentaires</h2>

                                                	<!-- Sélection du type d'activité -->
                                                	<div class="w-full">
                                                    	<label for="activityType" class="block text-nowrap">Type d'activité:</label>
                                                    	<input disabled id="activityType" name="activityType" type="text"
                                                        	class="border border-secondary  p-2 bg-gray-200 w-full text-gray-600"
                                                        	required value="<?php print_r($catOffre[0]['type_offre']); ?>">
                                                	</div>

                                                	<div class="flex flex-col w-full optionActivite optionVisite optionSpectacle optionRestauration optionParcAttraction hidden">
														<!-- Section pour choisir un tag -->
														<label for="tag-input" class="block text-nowrap">Tags :</label>
														<select type="text" id="tag-input"
															class="bg-white text-black py-2 px-4 border border-black w-full"
															placeholder="Ajouter un tag...">
															<option value="" class="hidden" selected>Rechercher un tag</option>
														</select>
													</div>

													<!-- Conteneurs des tags pour chaque type d'activité -->
													<div>
														<div class="tag-container flex flex-wrap p-2 rounded-lg optionActivite hidden" id="activiteTags"></div>
														<div class="tag-container flex flex-wrap p-2 rounded-lg optionVisite hidden" id="visiteTags"></div>
														<div class="tag-container flex flex-wrap p-2 rounded-lg optionSpectacle hidden" id="spectacleTags"></div>
														<div class="tag-container flex flex-wrap p-2 rounded-lg optionParcAttraction hidden" id="parcAttractionTags"></div>
														<div class="tag-container flex flex-wrap p-2 rounded-lg optionRestauration hidden" id="restaurationTags"></div>
													</div>

                                                	<!-- PARAMÈTRES DÉPENDANT DE LA CATÉGORIE DE L'OFFRE -->
                                                	<!-- Visite guidée -->
                                                	<!-- Visite -->
                                                	<div class="flex justify-between items-center w-full space-x-2 optionVisite hidden">
                                                    	<div class="inline-flex items-center space-x-4" onclick="toggleCheckbox('guide')">
                                                        	<p>Visite guidée :</p>
                                                        	<input type="checkbox" name="guide" id="guide" class="sr-only peer">
                                                        	<div
                                                            	class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800  peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border  after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                        	</div>
                                                        	<div class="space-x-2 w-fit flex items-center invisible peer-checked:visible">
                                                            	<p>
                                                                	Langues parlées :
                                                            	</p>
                                                            	<?php
                                                            	require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/langue_controller.php';
                                                            	$langueController = new LangueController();

                                                            	$langues = $langueController->getInfosAllLangues();

                                                            	foreach ($langues as $langue) { ?>
                                                                                    	<div class="w-fit p-2  border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
                                                                                        	onclick="toggleCheckbox('<?php echo 'langue' . $langue['id_langue']; ?>')">
                                                                                        	<label
                                                                                            	for="<?php echo 'langue' . $langue['id_langue']; ?>"><?php echo $langue['nom']; ?></label>
                                                                                        	<input type="checkbox" name="<?php echo 'langue' . $langue['id_langue']; ?>"
                                                                                            	id="<?php echo 'langue' . $langue['id_langue']; ?>" class="hidden">
                                                                                    	</div>
                                                            	<?php }
                                                            	?>
                                                        	</div>
                                                    	</div>
                                                	</div>

                                                	<!-- Âge requis -->
                                                	<!-- Activité, Parc d'attractions -->
                                                	<div
                                                    	class="flex justify-start items-center w-full space-x-2 optionActivite optionParcAttraction hidden">
                                                    	<label for="age" class="text-nowrap">Âge requis :</label>
                                                    	<input type="number" id="age" pattern="/d+/" min="0" max="125" name="age"
                                                        	class="border border-secondary  p-2 bg-white w-fit text-right">
                                                    	<p>an(s)</p>
                                                	</div>

                                                	<!-- Durée (HEURE & MIN) -->
                                                	<!-- Activité, Visite, Spectacle -->
                                                	<div
                                                    	class="flex justify-start items-center w-full space-x-1 optionActivite optionVisite optionSpectacle hidden">
                                                    	<label for="duree" class="text-nowrap">Durée :</label>
                                                    	<input type="number" name="hours" id="duree" pattern="/d+/" min="0" max="23"
                                                        	class="border border-secondary  p-2 bg-white w-fit text-right">
                                                    	<p>h </p>
                                                    	<input type="number" name="minutes" id="minute" pattern="/d+/" min="0" max="59"
                                                        	class="border border-secondary  p-2 bg-white w-fit text-right">
                                                    	<p>min</p>
                                                	</div>

                                                	<!-- Gamme de prix -->
                                                	<!-- Restauration -->
                                                	<div class="flex justify-start items-center w-full space-x-4 optionRestauration hidden">
                                                    	<label for="gamme" class="text-nowrap">Gamme de prix :</label>
                                                    	<div class="flex  space-x-2">
                                                        	<div>
                                                            	<input type="radio" id="€" name="gamme2prix" value="€" />
                                                            	<label for="€">€</label>
                                                        	</div>
                                                        	<div>
                                                            	<input type="radio" id="€€" name="gamme2prix" value="€€" checked />
                                                            	<label for="€€">€€</label>
                                                        	</div>
                                                        	<div>
                                                            	<input type="radio" id="€€€" name="gamme2prix" value="€€€" />
                                                            	<label for="€€€">€€€</label>
                                                        	</div>
                                                    	</div>
                                                	</div>

                                                	<!-- Capacité d'accueil -->
                                                	<!-- Spectacle -->
                                                	<div class="flex justify-start items-center w-full space-x-2 optionSpectacle hidden">
                                                    	<label for="capacite" class="text-nowrap">Capacité d'accueil :</label>
                                                    	<input type="number" name="capacite" id="capacite" pattern="/d+/" onchange="" min="0"
                                                        	class="border border-secondary  p-2 bg-white w-fit text-right">
                                                    	<p>personnes</p>
                                                	</div>

                                                	<!-- Nombre d'attractions -->
                                                	<!-- Parc d'attractions -->
                                                	<div class="flex justify-start items-center w-full space-x-2 optionParcAttraction hidden">
                                                    	<label for="nb_attractions" class="text-nowrap">Nombre d'attraction :</label>
                                                    	<input type="number" name="nb_attractions" id="nb_attractions" pattern="/d+/"
                                                        	onchange="" min="0"
                                                        	class="border border-secondary  p-2 bg-white w-fit text-right">
                                                    	<p>attractions</p>
                                                	</div>

                                                	<!-- Repas servis -->
													<div class="space-x-2 w-full flex justify-start items-center optionRestauration hidden">
														<p>Repas servis :</p>

														<div class="w-fit p-2 border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold" onclick="toggleCheckbox('repasPetitDejeuner')">
															<label for="repasPetitDejeuner">Petit-déjeuner</label>
															<input type="checkbox" name="repasPetitDejeuner" id="repasPetitDejeuner" class="hidden-checkbox" <?php echo in_array('Petit déjeuner', $repasNoms) ? 'checked' : ''; ?> hidden>
														</div>

														<div class="w-fit p-2 border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold" onclick="toggleCheckbox('repasBrunch')">
															<label for="repasBrunch">Brunch</label>
															<input type="checkbox" name="repasBrunch" id="repasBrunch" class="hidden-checkbox" <?php echo in_array('Brunch', $repasNoms) ? 'checked' : ''; ?> hidden >
														</div>

														<div class="w-fit p-2 border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold" onclick="toggleCheckbox('repasDejeuner')">
															<label for="repasDejeuner">Déjeuner</label>
															<input type="checkbox" name="repasDejeuner" id="repasDejeuner" class="hidden-checkbox" <?php echo in_array('Déjeuner', $repasNoms) ? 'checked' : ''; ?> hidden>
														</div>

														<div class="w-fit p-2 border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold" onclick="toggleCheckbox('repasDiner')">
															<label for="repasDiner">Dîner</label>
															<input type="checkbox" name="repasDiner" id="repasDiner" class="hidden-checkbox" <?php echo in_array('Dîner', $repasNoms) ? 'checked' : ''; ?> hidden >
														</div>

														<div class="w-fit p-2 border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold" onclick="toggleCheckbox('repasBoissons')">
															<label for="repasBoissons">Boissons</label>
															<input type="checkbox" name="repasBoissons" id="repasBoissons" class="hidden-checkbox" <?php echo in_array('Boissons', $repasNoms) ? 'checked' : ''; ?> hidden >
														</div>
													</div>
                                                	<!-- Plan du parc d'attraction -->
                                                	<!-- Parc d'attraction -->
                                                	<div class="flex flex-col justify-between w-full optionParcAttraction hidden">
                                                    	<label for="photo-plan" class="text-nowrap w-full">Plan du parc d'attraction :</label>
                                                    	<input type="file" name="photo-plan" id="photo-plan" class="text-center text-secondary block w-full
														border-dashed border-2 border-secondary  p-2
														file:mr-5 file:py-3 file:px-10
														file:
														file:text-small file:font-bold  file:text-secondary
														file:border file:border-secondary
														hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" accept=".svg,.png,.jpg" />
                                                	</div>

                                                	<!-- Services -->
                                                	<!-- Formulaire pour entrer les informations -->
                                                	<div class="flex flex-col justify-center items-center w-full space-y-4">
                                                    	<!-- PRESTATIONS -->
                                                    	<div class="w-full optionActivite hidden">
                                                        	<h2 class="text-h2 text-secondary">Prestation</h2>
                                                        	<table class="w-full">
                                                            	<thead>
                                                                	<th>
                                                                    	Nom
                                                                	</th>
                                                                	<th class="text-nowrap">
                                                                    	Est incluse ?
                                                                	</th>
                                                                	<th>
                                                                    	Actions
                                                                	</th>
                                                            	</thead>
                                                            	<tbody id="prestations">

                                                            	</tbody>
                                                            	<tr>
                                                                	<td class="w-full">
                                                                    	<input type="text" id="newPrestationName"
                                                                        	class="border border-secondary  p-2 bg-white w-full">
                                                                	</td>
                                                                	<td class="w-fit group">
                                                                    	<input type="checkbox" id="newPrestationInclude" class="hidden peer">
                                                                    	<label for="newPrestationInclude"
                                                                        	class="h-max w-full cursor-pointer flex justify-center items-center text-rouge-logo peer-checked:hidden">
                                                                        	<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                                            	viewBox="0 0 32 32" fill="none" stroke="currentcardColor"
                                                                            	stroke-width="2.5" stroke-linecap="round"
                                                                            	stroke-linejoin="round" class="lucide lucide-square-x">
                                                                            	<rect width="28" height="28" x="2" y="2" rx="4" ry="4" />
                                                                            	<path d="m24 8-16 16" />
                                                                            	<path d="m8 8 16 16" />
                                                                        	</svg>
                                                                    	</label>
                                                                    	<label for="newPrestationInclude"
                                                                        	class="hidden h-max w-full cursor-pointer justify-center items-center fill-secondary peer-checked:flex">
                                                                        	<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                                            	viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                            	<path
                                                                                	d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                                                                        	</svg>
                                                                    	</label>
                                                                	</td>
                                                                	<td class="w-fit">
                                                                    	<div class="h-max w-full cursor-pointer flex justify-center items-center"
                                                                        	id="addPrestationButton">
                                                                        	<svg xmlns="http://www.w3.org/2000/svg"
                                                                            	class="fill-secondary  border border-transparent hover:border-secondary border-box p-1"
                                                                            	width="32" height="32"
                                                                            	viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                            	<path
                                                                                	d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z" />
                                                                        	</svg>
                                                                    	</div>
                                                                	</td>
                                                            	</tr>
                                                        	</table>
                                                    	</div>

                                                    	<!-- HORAIRES -->
                                                    	<div
                                                        	class="w-full optionActivite optionVisite optionSpectacle optionParcAttraction optionRestauration hidden">

                                                        	<h2 class="text-h2 text-secondary">Horaires</h2>
                                                        	<table class="w-full table-auto">
                                                            	<thead>
                                                                	<th>
                                                                	</th>
                                                                	<th>
                                                                    	Lundi
                                                                	</th>
                                                                	<th>
                                                                    	Mardi
                                                                	</th>
                                                                	<th>
                                                                    	Mercredi
                                                                	</th>
                                                                	<th>
                                                                    	Jeudi
                                                                	</th>
                                                                	<th>
                                                                    	Vendredi
                                                                	</th>
                                                                	<th>
                                                                    	Samedi
                                                                	</th>
                                                                	<th>
                                                                    	Dimanche
                                                                	</th>
                                                            	</thead>
                                                            	<tbody>
                                                                	<tr>
                                                                    	<td>
                                                                        	Ouverture
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[lundi][ouverture]"
                                                                            	id="horaires[lundi][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['lundi']['ouverture']) ? date('H:i', strtotime($horaires['lundi']['ouverture'])) : ''; ?>">

                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mardi][ouverture]"
                                                                            	id="horaires[mardi][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mardi']['ouverture']) ? date('H:i', strtotime($horaires['mardi']['ouverture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mercredi][ouverture]"
                                                                            	id="horaires[mercredi][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mercredi']['ouverture']) ? date('H:i', strtotime($horaires['mercredi']['ouverture'])) : ''; ?>">

                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[jeudi][ouverture]"
                                                                            	id="horaires[jeudi][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['jeudi']['ouverture']) ? date('H:i', strtotime($horaires['jeudi']['ouverture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[vendredi][ouverture]"
                                                                            	id="horaires[vendredi][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['vendredi']['ouverture']) ? date('H:i', strtotime($horaires['vendredi']['ouverture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[samedi][ouverture]"
                                                                            	id="horaires[samedi][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['samedi']['ouverture']) ? date('H:i', strtotime($horaires['samedi']['ouverture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[dimanche][ouverture]"
                                                                            	id="horaires[dimanche][ouverture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['dimanche']['ouverture']) ? date('H:i', strtotime($horaires['dimanche']['ouverture'])) : ''; ?>">
                                                                    	</td>
                                                                	</tr>
                                                                	<tr>
                                                                    	<td>
                                                                        	Pause
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[lundi][pause]"
                                                                            	id="horaires[lundi][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['lundi']['pause_debut']) ? date('H:i', strtotime($horaires['lundi']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mardi][pause]"
                                                                            	id="horaires[mardi][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mardi']['pause_debut']) ? date('H:i', strtotime($horaires['mardi']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mercredi][pause]"
                                                                            	id="horaires[mercredi][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mercredi']['pause_debut']) ? date('H:i', strtotime($horaires['mercredi']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[jeudi][pause]"
                                                                            	id="horaires[jeudi][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['jeudi']['pause_debut']) ? date('H:i', strtotime($horaires['jeudi']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[vendredi][pause]"
                                                                            	id="horaires[vendredi][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['vendredi']['pause_debut']) ? date('H:i', strtotime($horaires['vendredi']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[samedi][pause]"
                                                                            	id="horaires[samedi][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['samedi']['pause_debut']) ? date('H:i', strtotime($horaires['samedi']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[dimanche][pause]"
                                                                            	id="horaires[dimanche][pause]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['dimanche']['pause_debut']) ? date('H:i', strtotime($horaires['dimanche']['pause_debut'])) : ''; ?>">
                                                                    	</td>
                                                                	</tr>
                                                                	<tr>
                                                                    	<td>
                                                                        	Reprise
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[lundi][reprise]"
                                                                            	id="horaires[lundi][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['lundi']['pause_fin']) ? date('H:i', strtotime($horaires['lundi']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mardi][reprise]"
                                                                            	id="horaires[mardi][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mardi']['pause_fin']) ? date('H:i', strtotime($horaires['mardi']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mercredi][reprise]"
                                                                            	id="horaires[mercredi][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mercredi']['pause_fin']) ? date('H:i', strtotime($horaires['mercredi']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[jeudi][reprise]"
                                                                            	id="horaires[jeudi][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['jeudi']['pause_fin']) ? date('H:i', strtotime($horaires['jeudi']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[vendredi][reprise]"
                                                                            	id="horaires[vendredi][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['vendredi']['pause_fin']) ? date('H:i', strtotime($horaires['vendredi']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[samedi][reprise]"
                                                                            	id="horaires[samedi][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['samedi']['pause_fin']) ? date('H:i', strtotime($horaires['samedi']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[dimanche][reprise]"
                                                                            	id="horaires[dimanche][reprise]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['dimanche']['pause_fin']) ? date('H:i', strtotime($horaires['dimanche']['pause_fin'])) : ''; ?>">
                                                                    	</td>
                                                                	</tr>
                                                                	<tr>
                                                                    	<td>
                                                                        	Fermeture
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[lundi][fermeture]"
                                                                            	id="horaires[lundi][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['lundi']['fermeture']) ? date('H:i', strtotime($horaires['lundi']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mardi][fermeture]"
                                                                            	id="horaires[mardi][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mardi']['fermeture']) ? date('H:i', strtotime($horaires['mardi']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[mercredi][fermeture]"
                                                                            	id="horaires[mercredi][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['mercredi']['fermeture']) ? date('H:i', strtotime($horaires['mercredi']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[jeudi][fermeture]"
                                                                            	id="horaires[jeudi][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['jeudi']['fermeture']) ? date('H:i', strtotime($horaires['jeudi']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[vendredi][fermeture]"
                                                                            	id="horaires[vendredi][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['vendredi']['fermeture']) ? date('H:i', strtotime($horaires['vendredi']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[samedi][fermeture]"
                                                                            	id="horaires[samedi][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['samedi']['fermeture']) ? date('H:i', strtotime($horaires['samedi']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                    	<td class="relative">
                                                                        	<input type="time" name="horaires[dimanche][fermeture]"
                                                                            	id="horaires[dimanche][fermeture]"
                                                                            	class="border border-secondary  p-2 bg-white mx-auto block"
                                                                            	value="<?php echo isset($horaires['dimanche']['fermeture']) ? date('H:i', strtotime($horaires['dimanche']['fermeture'])) : ''; ?>">
                                                                    	</td>
                                                                	</tr>
                                                            	</tbody>
                                                        	</table>
                                                        	<p>
                                                            	<span class="font-bold">Pro Tip :</span> Lorsque vous remplissez les horaires du
                                                            	lundi, elles mettent à jour les horaires des autres jours de la semaine.
                                                        	</p>
                                                    	</div>

                                                    	<!-- GRILLE TARIFAIRE -->
                                                    	<div class="w-full <?php if ($pro['data']['type'] === 'prive') {
                                                        	echo "optionActivite optionVisite optionSpectacle optionParcAttraction";
                                                    	} ?> hidden">
                                                        	<h2 class="text-h2 text-secondary">Grille tarifaire</h2>
                                                        	<table class="w-full">
                                                            	<thead>
                                                                	<th>
                                                                    	Titre
                                                                	</th>
                                                                	<th>
                                                                    	Prix<br>en €
                                                                	</th>
                                                                	<th>
                                                                    	Actions
                                                                	</th>
                                                            	</thead>
                                                            	<tbody id="grilleTarifaire">

                                                            	</tbody>
                                                            	<tr>
                                                                	<td class="w-full">
                                                                    	<input type="text" id="newPrixName"
                                                                        	class="border border-secondary  p-2 bg-white w-full">
                                                                	</td>
                                                                	<td class="w-fit">
                                                                    	<input type="number" id="newPrixValeur" min="0"
                                                                        	class="border border-secondary  p-2 bg-white">
                                                                	</td>
                                                                	<td class="w-fit">
                                                                    	<div class="h-max w-full cursor-pointer flex justify-center items-center"
                                                                        	id="addPriceButton">
                                                                        	<svg xmlns="http://www.w3.org/2000/svg"
                                                                            	class="fill-secondary  border border-transparent hover:border-secondary border-box p-1"
                                                                            	width="32" height="32"
                                                                            	viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                            	<path
                                                                                	d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z" />
                                                                        	</svg>
                                                                    	</div>
                                                                	</td>
                                                            	</tr>
                                                        	</table>
                                                    	</div>
                                                	</div>

                                                	<div class="<?php if ($pro['data']['type'] === 'prive') {
                                                    	echo "optionActivite optionVisite optionSpectacle optionRestauration optionParcAttraction";
                                                	} ?> hidden w-full">
                                                    	<h1 class="text-h2 text-secondary">Les options</h1>

                                                    	<!-- CGU -->
                                                    	<a href="/cgu" class="text-small underline text-secondary"> Voir les CGU</a>

                                                    	<!-- Radio button -->
                                                    	<div
                                                        	class="flex flex-row mb-4 content-center justify-between items-center text-secondary w-full">
                                                        	<!-- Sans option -->
                                                        	<div class="w-fit p-2  border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold text-lg"
                                                            	id="option-rien-div">
                                                            	<input type="radio" id="option-rien" name="option" value="1" class="hidden"
                                                                	checked="true" />
                                                            	<label for="option-rien">Sans option</label>
                                                        	</div>
                                                        	<?php
                                                        	require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/php_files/connect_to_bdd.php";

                                                        	$stmt = $dbh->prepare('SELECT * FROM sae_db._option ORDER BY prix_ht ASC');
                                                        	$stmt->execute();
                                                        	$options = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                        	foreach ($options as $option) {
                                                            	$nom_option = str_contains($option['nom'], 'relief') ? "option-relief" : "option-a-la-une";
                                                            	?>
                                                                                	<div class="w-fit p-2  border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold text-center text-lg"
                                                                                    	id="<?php echo $nom_option; ?>-div">
                                                                                    	<input type="radio" id="<?php echo $nom_option; ?>" name="option"
                                                                                        	value="<?php echo $option['nom']; ?>" class="hidden" />
                                                                                    	<label
                                                                                        	for="<?php echo $nom_option; ?>"><?php echo ucwords($option['nom']); ?><br>
                                                                                        	<span class="font-normal text-base">HT
                                                                                            	<?php echo $option['prix_ht']; ?>€/semaine<br>(TTC
                                                                                            	<?php echo $option['prix_ttc']; ?>€/semaine)</span>
                                                                                    	</label>
                                                                                	</div>
                                                        	<?php }
                                                        	?>
                                                    	</div>

                                                    	<div class="flex items-start hidden" id="option-data">
                                                        	<div class="flex flex-col justify-center w-full">
                                                            	<label for="start_date" class="text-nowrap">Début de la souscription :</label>
                                                            	<input type="date" id="start_date" name="start_date"
                                                                	class="border border-secondary  p-2 bg-white w-min"
                                                                	oninput="validateMonday(this)">
                                                            	<script>
                                                                	function validateMonday(input) {
                                                                    	const date = new Date(input.value);
                                                                    	if (date.getDay() !== 1) {
                                                                        	const nextMonday = new Date(date.setDate(date.getDate() + (1 + 7 - date.getDay()) % 7));
                                                                        	input.value = nextMonday.toISOString().split('T')[0];
                                                                    	}
                                                                	}

                                                                	document.getElementById('start_date').addEventListener('focus', function (e) {
                                                                    	e.target.setAttribute('min', getNextMonday());
                                                                    	e.target.value = getNextMonday();
                                                                	});

                                                                	function getNextMonday() {
                                                                    	const today = new Date();
                                                                    	const nextMonday = new Date(today.setDate(today.getDate() + (1 + 7 - today.getDay()) % 7));
                                                                    	return nextMonday.toISOString().split('T')[0];
                                                                	}
                                                            	</script>
                                                            	<p>
                                                                	Votre souscription doit commencer un lundi.
                                                            	</p>
                                                        	</div>

                                                        	<div class="flex flex-col justify-center w-full">
                                                            	<label for="duration" class="text-nowrap">Durée de la souscription :</label>
                                                            	<input type="number" id="duration" name="duration" min="1" max="4" value="1"
                                                                	class="border border-secondary  p-2 bg-white w-min">
                                                            	<script>

                                                                	document.getElementById('duration').addEventListener('change', function (event) {
                                                                    	const value = parseInt(event.target.value, 10);
                                                                    	if (value < 1) {
                                                                        	event.target.value = 1;
                                                                    	} else if (value > 4) {
                                                                        	event.target.value = 4;
                                                                    	}
                                                                	});
                                                            	</script>
                                                            	<p>
                                                                	La durée se compte en semaines.
                                                            	</p>
                                                        	</div>
                                                    	</div>
                                                	</div>
                                            	</div>
                                            	<!-- Modifier l'offre -->
                                                	<div class="w-full flex justify-center items-center optionActivite optionVisite optionSpectacle optionRestauration optionParcAttraction hidden">
                                                    	<button type="submit" class="bg-secondary text-white font-medium py-2 px-4 inline-flex items-center border border-transparent hover:bg-secondary/90 hover:border-secondary/90 focus:scale-[0.97] w-1/2 m-1 disabled:bg-gray-300 disabled:border-gray-300">Modifier l'offre</button>
                                                	</div>

                                        	</div>
                                        	<!-- Mettre la preview à droite du fleuve -->
                                        	<div
                                            	class="w-full min-w-[450px] max-w-[450px] h-screen flex justify-center items-center sticky top-0 part1 hidden">
                                            	<div class="h-fit w-full">
                                                	<!-- Affiche de la carte en fonction de l'option choisie et des informations rentrées au préalable. -->
                                                	<!-- Script > listener sur "change" sur les inputs radios (1 sur chaque) ; si input en relief ou À la une, ajouter(.add('active')) à la classlist(.classList) du div {card-preview} "active", sinon l'enlever(.remove('active')) -->
                                                	<div class="card active relative bg-base300  flex flex-col w-full"
                                                    	id="card-preview">
                                                    	<script>
                                                        	// Fonction pour activer ou désactiver la carte en fonction de l'option choisie
                                                        	function toggleCardPreview(option) {
                                                            	// Récupérer l'élément de la carte
                                                            	const cardPreview = document.getElementById("card-preview");
                                                            	// Ajouter ou retirer la classe active en fonction de l'option choisie
                                                            	if (option === "option-rien") {
                                                                	cardPreview.classList.remove("active");
                                                            	} else {
                                                                	cardPreview.classList.add("active");
                                                            	}
                                                        	}
                                                        	// Ajouter un EventListener pour détecter les changements dans les options
                                                        	optionData = document.getElementById("option-data");
                                                        	document.getElementById("option-rien-div").addEventListener("click", function () {
                                                            	toggleRadio("option-rien");
                                                            	toggleCardPreview("option-rien");
                                                            	optionData.classList.add('hidden');
                                                        	});
                                                        	document.getElementById("option-relief-div").addEventListener("click", function () {
                                                            	toggleRadio("option-relief");
                                                            	toggleCardPreview("option-relief");
                                                            	optionData.classList.remove('hidden');
                                                        	});
                                                        	document.getElementById("option-a-la-une-div").addEventListener("click", function () {
                                                            	toggleRadio("option-a-la-une");
                                                            	toggleCardPreview("option-a-la-une");
                                                            	optionData.classList.remove('hidden');
                                                        	});
                                                    	</script>
                                                    	<!-- En tête -->
                                                    	<div
                                                        	class="en-tete absolute top-0 w-72 max-w-full bg-blur/75 backdrop-blur left-1/2 -translate-x-1/2 ">
                                                        	<!-- Mise à jour du titre en temps réel -->
                                                        	<h3 class="text-center font-bold" id="preview-titre"></h3>
                                                        	<script>
                                                            	document.getElementById("preview-titre").textContent = document.getElementById("titre").value ?
                                                                	document.getElementById("titre").value
                                                                	:
                                                                	// Si le titre est vide, afficher le placeholder du titre
                                                                	document.getElementById("titre").placeholder;
                                                            	document
                                                                	.getElementById("titre")
                                                                	.addEventListener("input", function () {
                                                                    	document.getElementById("preview-titre").textContent = document.getElementById("titre").value ?
                                                                        	document.getElementById("titre").value
                                                                        	:
                                                                        	// Si le titre est vide, afficher le placeholder du titre
                                                                        	document.getElementById("titre").placeholder;
                                                                	});
                                                        	</script>
                                                        	<div class="flex w-full justify-between px-2">
                                                            	<!-- Mise à jour de l'auteur en temps réel -->
                                                            	<p class="text-small" id="preview-auteur"></p>
                                                            	<script>
                                                                	document.getElementById("preview-auteur").textContent =
                                                                    	document.getElementById("auteur").innerText;
                                                            	</script>
                                                            	<p class="text-small" id="preview-activite"></p>
                                                            	<!-- Mise à jour de l'activité en fonction de la sélection -->
                                                            	<script>
                                                                	// Fonction pour mettre à jour la sélection d'activité
                                                                	function updateActivite() {
                                                                    	// Récupérer la valeur sélectionnée dans le sélecteur
                                                                    	const selectedActivite =
                                                                        	document.getElementById("activityType").value;
                                                                    	// Transforme la value en texte propre
                                                                    	switch (selectedActivite) {
                                                                        	case "activite":
                                                                            	document.getElementById(
                                                                                	"preview-activite"
                                                                            	).textContent = "Activité";
                                                                            	break;
                                                                        	case "visite":
                                                                            	document.getElementById(
                                                                                	"preview-activite"
                                                                            	).textContent = "Visite";
                                                                            	break;
                                                                        	case "spectacle":
                                                                            	document.getElementById(
                                                                                	"preview-activite"
                                                                            	).textContent = "Spectacle";
                                                                            	break;
                                                                        	case "parc_attraction":
                                                                            	document.getElementById(
                                                                                	"preview-activite"
                                                                            	).textContent = "Parc d'attraction";
                                                                            	break;
                                                                        	case "restauration":
                                                                            	document.getElementById(
                                                                                	"preview-activite"
                                                                            	).textContent = "Restauration";
                                                                            	break;
                                                                        	default:
                                                                            	document.getElementById(
                                                                                	"preview-activite"
                                                                            	).textContent = "Type d'activité";
                                                                    	}
                                                                	}
                                                                	// Ajouter un EventListener pour détecter les changements dans le sélecteur
                                                                	document
                                                                    	.getElementById("activityType")
                                                                    	.addEventListener("change", updateActivite);
                                                                	// Appeler la fonction une première fois pour l'initialisation avec la valeur par défaut
                                                                	updateActivite();
                                                            	</script>
                                                        	</div>
                                                    	</div>
                                                    	<!-- Image de fond -->
                                                    	<img class="h-48 w-full  object-cover" src="/public/images/offres/<?php echo $images['carte']; ?>"
                                                        	alt="Image promotionnelle de l'offre" id="preview-image" />
                                                    	<script>
                                                        	document
                                                            	.getElementById("photo-upload-carte")
                                                            	.addEventListener("change", function (event) {
                                                                	const file = event.target.files[0]; // Récupérer le fichier sélectionné
                                                                	const previewImage =
                                                                    	document.getElementById("preview-image"); // Élément d'image à mettre à jour

                                            	if (file) {
                                                	const reader = new FileReader(); // Créer un nouvel objet FileReader
                                                	reader.onload = function (e) {
                                                    	previewImage.src = e.target.result; // Mettre à jour la source de l'image avec le fichier
                                                	};
                                                	reader.readAsDataURL(file); // Lire le fichier comme une URL de données
                                            	} else {
                                                	previewImage.src = "#"; // Image par défaut ou vide si aucun fichier
                                            	}
                                        	});
                                	</script>
                                	<!-- Infos principales -->
                                	<div class="infos flex items-center justify-around gap-2 px-2 w-full max-w-full">
                                    	<!-- Localisation -->
                                    	<div
                                        	class="localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center">
                                        	<i class="fa-solid fa-location-dot"></i>
                                        	<!-- Mise à jour de la ville en temps réel -->
                                        	<p class="text-small" id="preview-locality"></p>
                                        	<script>
                                            	document.getElementById(
                                                	"preview-locality"
                                            	).textContent =
                                                	document.getElementById("locality").value ? document.getElementById("locality").value : document.getElementById("locality").placeholder
                                            	document
                                                	.getElementById("locality")
                                                	.addEventListener("input",  () => {
                                                    	document.getElementById(
                                                        	"preview-locality"
                                                    	).textContent =
                                                        	document.getElementById("locality").value ? document.getElementById("locality").value : document.getElementById("locality").placeholder
                                                	});
                                        	</script>
                                        	<!-- Mise à jour du code postal en temps réel -->
                                        	<p class="text-small" id="preview-postal_code"></p>
                                        	<script>
                                            	document.getElementById(
                                                	"preview-postal_code"
                                            	).textContent =
                                                	document.getElementById("postal_code").value ? document.getElementById("postal_code").value : document.getElementById("postal_code").placeholder
                                            	document
                                                	.getElementById("postal_code")
                                                	.addEventListener("input", function () {
                                                    	document.getElementById(
                                                        	"preview-postal_code"
                                                    	).textContent =
                                                        	document.getElementById("postal_code").value ? document.getElementById("postal_code").value : document.getElementById("postal_code").placeholder;
                                                	});
                                        	</script>
                                    	</div>
                                    	<hr class="h-20 border-black border" />
                                    	<!-- Résumé de l'offre -->
                                    	<div
                                        	class="description py-2 flex flex-col gap-2 justify-center w-full max-w-[300px]">
                                        	<div class="p-1 w-full flex justify-center items-center">
                                            	<!-- <p
                            	class="text-white text-center text-small w-full font-bold"
                          	></p> -->
                                            	<!-- Mise à jour du tag en temps réel -->
                                            	<p class="text-white text-center rounded-lg bg-secondary font-bold w-fit p-2"
                                                	id="preview-tag-input">
                                            	</p>
                                            	<script>
                                                	function refreshTagPreview() {
                                                    	const tagPreview = document.getElementById(
                                                        	"preview-tag-input"
                                                    	)

                                                    	document.querySelectorAll('.tag-container')?.forEach(container => {
                                                        	if (!container.classList.contains('hidden')) {
                                                            	const tags = Array.from(container.children).map(tag => tag.childNodes[0].nodeValue).join(', ');
                                                            	tagPreview.textContent = tags !== '' ? (tags.length > 30 ? tags.slice(0, 30) + "..." : tags) : "recherchez un tag";
                                                        	}
                                                    	});
                                                	}
                                                	refreshTagPreview();

                                                                    	Array.from(document
                                                                        	.getElementsByClassName("tag-container")).forEach(
                                                                            	(container) => {
                                                                                	const observer = new MutationObserver(refreshTagPreview);
                                                                                	observer.observe(container, { childList: true });
                                                                            	}
                                                                        	)
                                                                	</script>
                                                            	</div>
                                                            	<!-- Mise à jour du résumé en temps réel -->
                                                            	<p class="line-clamp-2 text-small text-center break-words max-w-full"
                                                                	id="preview-resume"></p>
                                                            	<script>
                                                                	document.getElementById("preview-resume").textContent =
                                                                    	document.getElementById("resume").value ? document.getElementById("resume").value : document.getElementById("resume").placeholder
                                                                	document
                                                                    	.getElementById("resume")
                                                                    	.addEventListener("input", function () {
                                                                        	document.getElementById("preview-resume").textContent =
                                                                            	document.getElementById("resume").value ? document.getElementById("resume").value : document.getElementById("resume").placeholder;
                                                                    	});
                                                            	</script>
                                                        	</div>
                                                        	<hr class="h-20 border-black border" />
                                                        	<!-- Notation et Prix -->
                                                        	<div
                                                            	class="localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center">
                                                            	<p class="text-small" id="preview-prix-diff">€</p>
                                                            	<!-- Valeur par défaut -->
                                                        	</div>
                                                        	<!-- Mise à jour de la gamme de prix -->
                                                        	<script>
                                                            	// Fonction pour mettre à jour la gamme de prix
                                                            	function updatePrixDiff() {
                                                                	// Récupérer la valeur du bouton radio sélectionné
                                                                	const selectedPrix = document.querySelector(
                                                                    	'input[name="gamme2prix"]:checked'
                                                                	).value;
                                                                	// Mettre à jour le texte dans la prévisualisation
                                                                	document.getElementById("preview-prix-diff").textContent =
                                                                    	selectedPrix;
                                                            	}

                                                            	// Ajouter un EventListener pour détecter les changements dans le groupe de boutons radio
                                                            	document
                                                                	.querySelectorAll('input[name="gamme2prix"]')
                                                                	.forEach((radio) => {
                                                                    	radio.addEventListener("change", updatePrixDiff);
                                                                	});

                                                            	// Appeler la fonction une première fois pour l'initialisation avec la valeur par défaut
                                                            	updatePrixDiff();
                                                        	</script>
                                                    	</div>
                                                	</div>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</form>
                            	</div>

        	<!-- FOOTER -->
        	<div class="w-full">
            	<?php
            	include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer-pro.php';
            	?>
        	</div>
    	</div>

    	<script src="/scripts/tagManager.js"></script>
    	<script>
			document.addEventListener('DOMContentLoaded', () => {
				<?php
				if ($catOffre[0]['type_offre'] === 'restauration') {
					require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_resto_controller.php';
					$tagRestaurationController = new TagRestoController();
					$tags = $tagRestaurationController->getTagResto($offre['id_offre']);
				} else {
					require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tag_controller.php';
					$tagController = new TagController();
					$tags = $tagController->getInfosTag($offre['id_offre']);
				}
				?>
				const existingTags = <?php echo json_encode($tags); ?>;
				console.log(existingTags);
				const tagManager = new TagManager('tag-input', existingTags);
			});
		</script>
    	<script src="/scripts/priceManager.js"></script>
    	<script src="/scripts/prestationManager.js"></script>
    	<script src="/scripts/optionToggler.js"></script>
    	<script>
        	// Lors de l'appui sur entrer, ne pas soumettre le formulaire
        	document.getElementById('formulaire').addEventListener('keydown', function (event) {
            	if (event.key === 'Enter') {
                	event.preventDefault();
            	}
        	});

                            	// Fonction pour afficher la partie 1 du formulaire
                            	function showPart1() {
                                	// Récupérer les éléments à afficher
                                	const elements = document.getElementsByClassName("part1");
                                	// Afficher les éléments
                                	for (let i = 0; i < elements.length; i++) {
                                    	elements[i].classList.remove("hidden");
                                	}
                            	}

                            	// Fonction pour afficher la partie 2 du formulaire
                            	function showPart2() {
                                	// Récupérer les éléments à afficher
                                	const part2 = document.querySelector(".part2");
                                	// Afficher les éléments
                                	part2.classList.remove("hidden");

                                	const activityType = document.querySelector("#activityType").value;


                                	hide()
                                	switch (activityType) {
                                    	case 'activite':
                                        	show(activityTypes.activite);
                                        	break;
                                    	case 'restauration':
                                        	show(activityTypes.restauration);
                                        	break;
                                    	case 'visite':
                                        	show(activityTypes.visite);
                                        	break;
                                    	case 'spectacle':
                                        	show(activityTypes.spectacle);
                                        	break;
                                    	case 'parc_attraction':
                                        	show(activityTypes.parc_attraction);
                                        	break;
                                    	default:
                                        	break;
                                	};
                            	}

                            	function showPart3() {
                                	document.getElementById("submitPart3").removeAttribute("disabled");
                            	}

                            	function hidePart3() {
                                	document.getElementById("submitPart3").setAttribute("disabled", "true");
                            	}

                            	function checkPart1Validity() {
                                	return true;
                            	}

                            	function checkPart2Validity(fieldChanged) {
                                	return true;
                            	}

                            	function checkPart3Validity(fieldChanged) {
                                	return true;
                            	}

                            	// Afficher toutes les parties du formulaire au chargement de la page
                            	showPart1();
                            	showPart2();
                            	showPart3();

                            	function toggleCheckbox(id) {
                                	const checkbox = document.getElementById(id);
                                	checkbox.checked = !checkbox.checked;

                            	}

                            	function toggleRadio(id) {
                                	const radio = document.getElementById(id);
                                	radio.checked = true;
                            	}

                            	document.querySelectorAll('input[name="type_offre"]').forEach((radio) => {
                                	radio.addEventListener("change", () => {
                                    	checkPart1Validity();
                                	});
                            	});

                            	const fields = document.querySelectorAll('input, textarea, select');

                            	fields.forEach((field) => {
                                	field.addEventListener('input', (e) => {
                                    	checkPart3Validity(field);
                                    	if (field.nodeName === 'INPUT' && field.attributes['type'].value === 'number') {
                                        	field.value = field.value.replace(/[^0-9]/g, '');
                                    	}
                                	});
                            	});
                        	</script>
                        	<script>
                            	// TODO: à fix : Lors de la suppression du lundi, suppression du reste

                            	for (const field of ['ouverture', 'pause', 'reprise', 'fermeture']) {
                                	const lundi = document.getElementById(`horaires[lundi][${field}]`);
                                	lundi.addEventListener('change', () => {
                                    	for (const jour of ['mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']) {
                                        	const element = document.getElementById(`horaires[${jour}][${field}]`);
                                        	element.value = lundi.value;
                                    	}
                                	});
                            	}

                            	const tagInput = document.querySelector("#tag-input");
                            	console.log(tagInput);
                            	tagInput.value = "test";
                        	</script>

	<?php } ?>
</body>

</html>


