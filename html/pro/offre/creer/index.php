<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="icon" type="image" href="/public/images/favicon.png">

	<link rel="stylesheet" href="/styles/input.css">
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="/styles/config.js"></script>
	<script type="module" src="/scripts/main.js" defer></script>
	<script type="text/javascript"
		src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCzthw-y9_JgvN-ZwEtbzcYShDBb0YXwA8&language=fr"></script>
	<script type="text/javascript" src="/scripts/autocomplete.js"></script>
	<script src="/scripts/utils.js"></script>

	<title>Création d'offre - Professionnel - PACT</title>
</head>

<!-- 
	À FAIRE :
	X lier les champs, VILLE, CODE POSTAL, ADRESSE à l'aide de l'API GOOGLE.
	- Faire les champs de recherches avec TAG, qui sera aussi utilisé pour VISITE : LANGUE, RESTAURATION : REPAS SERVIS (Petit-dej, Brunch, Dej, Diner, Boissons)
	- Appliquer les scripts à tous les champs pour s'assurer de leur conformité
	- Faire le PHP
	- Faire du JS

	TODO : Ajouter des 'i' d'informations pour expliquer les champs
	TODO : Enlever la grille tarifaire pour les restaurants
-->

<body>
	<?php
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
		echo "Adresse insérée<br>";

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
				echo "Activité insérée<br>";
				break;

			case 'visite':

				require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/visite_controller.php';

				$visiteController = new VisiteController();
				$id_offre = $visiteController->createVisite($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $avec_guide);

				if ($id_offre < 0) {
					echo "Erreur lors de l'insertion : " . $id_offre;
					BDD::rollbackTransaction();
				}
				echo "Visite insérée<br>";
				break;

			case 'spectacle':

				require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/spectacle_controller.php';

				$spectacleController = new SpectacleController();
				$id_offre = $spectacleController->createSpectacle($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $dureeFormatted, $capacite);

				if ($id_offre < 0) {
					echo "Erreur lors de l'insertion : " . $id_offre;
					BDD::rollbackTransaction();
				}
				echo "Spectacle inséré<br>";
				break;

			case 'parc_attraction':

				require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/parc_attraction_controller.php';

				$parcAttractionController = new ParcAttractionController();
				$id_offre = $parcAttractionController->createParcAttraction($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age);

				if ($id_offre < 0) {
					echo "Erreur lors de l'insertion : " . $id_offre;
					BDD::rollbackTransaction();
				}
				echo "Parc d'attraction inséré<br>";
				break;

			case 'restauration':

				require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';

				$restaurationController = new RestaurationController();
				$id_offre = $restaurationController->createRestauration($description, $resume, $prixMin, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix, $id_type_repas);

				if ($id_offre < 0) {
					echo "Erreur lors de l'insertion : " . $id_offre;
					BDD::rollbackTransaction();
				}
				echo "Restauration insérée<br>";
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
				echo "tag " . $tag . " inséré<br>";
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
		echo "Image de la carte insérée<br>";

		// *** DETAIL
		for ($i = 0; $i < count($_FILES['photo-detail']['name']); $i++) {
			if ($imageController->uploadImage($id_offre, 'detail', $_FILES['photo-detail']['tmp_name'][$i], explode('/', $_FILES['photo-detail']['type'][$i])[1])) {
				echo "Erreur lors de l'upload de l'image de détail.";
				BDD::rollbackTransaction();
				exit;
			}
		}
		echo "Images de détails insérées<br>";

		if ($activity === 'parc_attraction') {
			if ($imageController->uploadImage($id_offre, 'plan', $_FILES['photo-plan']['tmp_name'], explode('/', $_FILES['photo-plan']['type'])[1])) {
				echo "Erreur lors de l'upload de l'image du plan.";
				BDD::rollbackTransaction();
				exit;
			}
		}
		echo "Image du plan insérée<br>";

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
			echo "Langues liées<br>";
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
			echo "Types de repas liés<br>";
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
			echo "Prestations liées<br>";
		}

		// Insérer les horaires dans la base de données
		require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/horaire_controller.php';
		$horaireController = new HoraireController();

		foreach ($horaires as $jour) {
			$horaireController->createHoraire($jour['ouverture'], $jour['fermeture'], $jour['pause'], $jour['reprise'], $id_offre);
		}
		echo "Horaires insérés<br>";

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
		echo "Tarifs insérés<br>";

		BDD::commitTransaction();
		// header('location: /pro');
	} else { ?>
		<!-- Conteneur principal pour le contenu -->
		<div class="flex flex-col w-full justify-between items-center align-baseline min-h-screen">

			<div class="w-full mb-20">
				<!-- Inclusion du header -->
				<?php
				include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
				?>
			</div>

			<div class="min-w-[1280px] max-w-[1280px] flex flex-col items-center justify-center py-8 rounded-xl">
				<!-- Lien de retour avec une icône et un titre -->
				<a href="" onclick="history.back()" class="flex w-full gap-4 items-center content-center space-x-">
					<i class="fa-solid fa-arrow-left fa-2xl"></i>
					<div class="my-2">
						<h1 class="text-h1">Création d'offre</h1>
					</div>
				</a>
				<!-- Section de sélection de l'offre -->
				<form id="formulaire" action="" method="POST" class="block w-full space-y-8" enctype="multipart/form-data">
					<div class="grid grid-cols-2 justify-around items-evenly gap-6 w-full md:space-y-0 md:flex-nowrap">
						<!-- Carte de l'offre gratuite -->
						<div
							class="border border-secondary rounded-lg flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white md:h-full hidden">
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
						<!-- Carte de l'offre standard -->
						<div
							class="border border-primary rounded-lg flex-col justify-center w-full text-primary p-4 has-[:checked]:bg-primary has-[:checked]:text-white md:h-full">
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
						<!-- Carte de l'offre premium -->
						<div
							class="border border-secondary rounded-lg flex-col justify-center w-full text-secondary p-4 has-[:checked]:bg-secondary has-[:checked]:text-white md:h-full">
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
						</div>
					</div>
					<div class="w-full flex space-x-12">
						<div class="w-full">
							<div class="w-full flex flex-col justify-center items-center space-y-4 part1 hidden">
								<h2 class="w-full text-h2 text-secondary">Informations</h2>

								<!-- Titre -->
								<div class="flex flex-col justify-center w-full">
									<label for="titre" class="text-nowrap">Titre :</label>
									<input type="text" id="titre"
										class="border border-secondary rounded-lg p-2 bg-white w-full" name="titre"
										placeholder="Escapade En Arvor" required>
								</div>

								<!-- Auteur -->
								<div class="flex flex-col w-full">
									<label for="auteur" class="text-nowrap">Auteur :</label>
									<p id="auteur"
										class="border border-secondary rounded-lg p-2 bg-gray-200 w-full text-gray-600">
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
										name="user_input_autocomplete_address" placeholder="21, rue de la Paix"
										class="border border-secondary rounded-lg p-2 bg-white w-full" required>
								</div>

								<div class="justify-between items-center w-full">
									<label for="locality" class="text-nowrap">Ville :</label>
									<input type="text" id="locality" name="locality" placeholder="Rennes"
										class="border border-secondary rounded-lg p-2 bg-white w-full" required>

									<label for="postal_code" class="text-nowrap">Code postal :</label>
									<input type="number" min="0" step="10" max="57000" id="postal_code" name="postal_code"
										placeholder="35000"
										class="border border-secondary rounded-lg p-2 bg-white w-24 w-full" required>
								</div>

								<div class="w-full justify-between">
									<!-- Photo principale -->
									<div class="flex flex-col justify-between w-full">
										<label for="photo-upload-carte" class="text-nowrap w-full">Photo de la carte
											:</label>
										<input type="file" name="photo-upload-carte" id="photo-upload-carte" class="text-center text-secondary block w-full
									border-dashed border-2 border-secondary rounded-lg p-2
									file:mr-5 file:py-3 file:px-10
									file:rounded-lg
									file:text-small file:font-bold  file:text-secondary
									file:border file:border-secondary
									hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" accept=".svg,.png,.jpg,.jpeg,.webp"
											required />
									</div>

									<!-- Photos détaillée -->
									<div class="flex flex-col justify-between w-full">
										<label for="photo-detail[]" class="text-nowrap w-full">Photos de l'offre détaillée:
										</label>
										<input type="file" name="photo-detail[]" id="photo-detail[]" class="text-center text-secondary block w-full
											border-dashed border-2 border-secondary rounded-lg p-2
											file:mr-5 file:py-3 file:px-10
											file:rounded-lg
											file:text-small file:font-bold  file:text-secondary
											file:border file:border-secondary
											hover:file:cursor-pointer hover:file:bg-secondary hover:file:text-white" accept=".svg,.png,.jpg,.jpeg,.webp"
											multiple />
									</div>
								</div>

								<!-- Résumé -->
								<div class="flex flex-col items-center w-full max-w-full">
									<label for="resume" class="text-nowrap w-full">Résumé :</label>
									<textarea id="resume" name="resume"
										class="border border-secondary rounded-lg p-2 bg-white w-full" rows="4"
										placeholder="Le résumé visible sur la carte de l'offre." required></textarea>

								</div>

								<!-- Description -->
								<div class="flex flex-col items-center w-full">
									<label for="description" class="text-nowrap w-full">Description :</label>
									<textarea id="description" name="description"
										class="border border-secondary rounded-lg p-2 bg-white w-full" rows="11"
										placeholder="La description visible dans les détails de l'offre."
										required></textarea>
								</div>

								<!-- Accessibilité -->
								<div class="flex flex-col justify-between items-center w-full">
									<label for="accessibilite" class="text-nowrap w-full">Accessibilité :</label>
									<textarea id="accessibilite" name="accessibilite"
										class="border border-secondary rounded-lg p-2 bg-white w-full" rows="5"
										placeholder="Une description de l'accessibilité pour les personnes en situation de handicap, visible dans les détails de l'offre."></textarea>
								</div>
							</div>
							<div class="w-full flex flex-col justify-center items-center space-y-4 part2 hidden">
								<h2 class="w-full text-h2 text-secondary">Informations supplémentaires</h2>

								<!-- Sélection du type d'activité -->
								<div class="w-full">
									<label for="activityType" class="block text-nowrap">Type d'activité:</label>
									<select id="activityType" name="activityType"
										class="bg-white text-black py-2 px-4 border border-black rounded-lg w-full"
										required>
										<option value="selection" selected hidden>Quel type d'activité ?</option>
										<option value="activite" id="activite">Activité</option>
										<option value="visite" id="visite">Visite</option>
										<option value="spectacle" id="spectacle">Spectacle</option>
										<option value="parc_attraction" id="parc_attraction">Parc d'attraction</option>
										<option value="restauration" id="restauration">Restauration</option>
									</select>
								</div>

								<div
									class="flex flex-col w-full optionActivite optionVisite optionSpectacle optionRestauration optionParcAttraction hidden">
									<label for="tag-input" class="block text-nowrap">Tags :</label>
									<select type="text" id="tag-input"
										class="bg-white text-black py-2 px-4 border border-black rounded-lg w-full"
										placeholder="Ajouter un tag...">
										<option value="" class="hidden" selected>Rechercher un tag</option>
									</select>
								</div>

								<div>
									<div class="tag-container flex flex-wrap p-2 rounded-lg optionActivite hidden"
										id="activiteTags"></div>
									<div class="tag-container flex flex-wrap p-2 rounded-lg optionVisite hidden"
										id="visiteTags"></div>
									<div class="tag-container flex flex-wrap p-2 rounded-lg optionSpectacle hidden"
										id="spectacleTags"></div>
									<div class="tag-container flex flex-wrap p-2 rounded-lg optionParcAttraction hidden"
										id="parcAttractionTags"></div>
									<div class="tag-container flex flex-wrap p-2 rounded-lg optionRestauration hidden"
										id="restaurationTags"></div>
								</div>

								<!-- PARAMÈTRES DÉPENDANT DE LA CATÉGORIE DE L'OFFRE -->
								<!-- Visite guidée -->
								<!-- Visite -->
								<div class="flex justify-between items-center w-full space-x-2 optionVisite hidden">
									<div class="inline-flex items-center cursor-pointer space-x-4"
										onclick="toggleCheckbox('guide')">
										<p>Visite guidée :</p>
										<input type="checkbox" name="guide" id="guide" value="" class="sr-only peer">
										<div
											class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
										</div>
										<div class="space-x-2 w-fit flex items-center invisible peer-checked:visible">
											<p>
												Langues parlées :
											</p>
											<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
												onclick="toggleCheckbox('langueFR')">
												<label for="langueFR">Français</label>
												<input type="checkbox" name="langueFR" id="langueFR" class="hidden"
													checked="true">
											</div>
											<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
												onclick="toggleCheckbox('langueEN')">
												<label for="langueEN">Anglais</label>
												<input type="checkbox" name="langueEN" id="langueEN" class="hidden">
											</div>
											<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
												onclick="toggleCheckbox('langueES')">
												<label for="langueES">Espagnol</label>
												<input type="checkbox" name="langueES" id="langueES" class="hidden">
											</div>
											<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
												onclick="toggleCheckbox('langueDE')">
												<label for="langueDE">Allemand</label>
												<input type="checkbox" name="langueDE" id="langueDE" class="hidden">
											</div>
										</div>
									</div>
								</div>

								<!-- Âge requis -->
								<!-- Activité, Parc d'attractions -->
								<div
									class="flex justify-start items-center w-full space-x-2 optionActivite optionParcAttraction hidden">
									<label for="age" class="text-nowrap">Âge requis :</label>
									<input type="number" id="age" pattern="/d+/" min="0" max="125" name="age"
										class="border border-secondary rounded-lg p-2 bg-white w-fit text-right">
									<p>an(s)</p>
								</div>

								<!-- Durée (HEURE & MIN) -->
								<!-- Activité, Visite, Spectacle -->
								<div
									class="flex justify-start items-center w-full space-x-1 optionActivite optionVisite optionSpectacle hidden">
									<label for="duree" class="text-nowrap">Durée :</label>
									<input type="number" name="hours" id="duree" pattern="/d+/" min="0" max="23"
										class="border border-secondary rounded-lg p-2 bg-white w-fit text-right">
									<p>h </p>
									<input type="number" name="minutes" id="minute" pattern="/d+/" min="0" max="59"
										class="border border-secondary rounded-lg p-2 bg-white w-fit text-right">
									<p>min</p>
								</div>

								<!-- Gamme de prix -->
								<!-- Restauration -->
								<div class="flex justify-start items-center w-full space-x-4 optionRestauration hidden">
									<label for="gamme" class="text-nowrap">Gamme de prix :</label>
									<div class="flex  space-x-2">
										<div>
											<input type="radio" id="prix1" name="gamme2prix" value="prix1" />
											<label for="prix1">€</label>
										</div>
										<div>
											<input type="radio" id="prix2" name="gamme2prix" value="prix2" checked />
											<label for="prix2">€€</label>
										</div>
										<div>
											<input type="radio" id="prix3" name="gamme2prix" value="prix3" />
											<label for="prix3">€€€</label>
										</div>
									</div>
								</div>

								<!-- Capacité d'accueil -->
								<!-- Spectacle -->
								<div class="flex justify-start items-center w-full space-x-2 optionSpectacle hidden">
									<label for="capacite" class="text-nowrap">Capacité d'accueil :</label>
									<input type="number" name="capacite" id="capacite" pattern="/d+/" onchange="" min="0"
										class="border border-secondary rounded-lg p-2 bg-white w-fit text-right">
									<p>personnes</p>
								</div>

								<!-- Nombre d'attractions -->
								<!-- Parc d'attractions -->
								<div class="flex justify-start items-center w-full space-x-2 optionParcAttraction hidden">
									<label for="nb_attractions" class="text-nowrap">Nombre d'attraction :</label>
									<input type="number" name="nb_attractions" id="nb_attractions" pattern="/d+/"
										onchange="" min="0"
										class="border border-secondary rounded-lg p-2 bg-white w-fit text-right">
									<p>attractions</p>
								</div>

								<!-- Repas servis -->
								<div class="space-x-2 w-full flex justify-start items-center optionRestauration hidden">
									<p>
										Repas servis :
									</p>
									<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
										onclick="toggleCheckbox('repasPetitDejeuner')">
										<label for="repasPetitDejeuner">Petit-déjeuner</label>
										<input type="checkbox" name="repasPetitDejeuner" id="repasPetitDejeuner"
											class="hidden">
									</div>
									<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
										onclick="toggleCheckbox('repasBrunch')">
										<label for="repasBrunch">Brunch</label>
										<input type="checkbox" name="repasBrunch" id="repasBrunch" class="hidden">
									</div>
									<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
										onclick="toggleCheckbox('repasDejeuner')">
										<label for="repasDejeuner">Déjeuner</label>
										<input type="checkbox" name="repasDejeuner" id="repasDejeuner" class="hidden">
									</div>
									<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
										onclick="toggleCheckbox('repasDiner')">
										<label for="repasDiner">Dîner</label>
										<input type="checkbox" name="repasDiner" id="repasDiner" class="hidden">
									</div>
									<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
										onclick="toggleCheckbox('repasBoissons')">
										<label for="repasBoissons">Boissons</label>
										<input type="checkbox" name="repasBoissons" id="repasBoissons" class="hidden">
									</div>
								</div>

								<!-- Plan du parc d'attraction -->
								<!-- Parc d'attraction -->
								<div class="flex flex-col justify-between w-full optionParcAttraction hidden">
									<label for="photo-plan" class="text-nowrap w-full">Plan du parc d'attraction :</label>
									<input type="file" name="photo-plan" id="photo-plan" class="text-center text-secondary block w-full
							border-dashed border-2 border-secondary rounded-lg p-2
							file:mr-5 file:py-3 file:px-10
							file:rounded-lg
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
														class="border border-secondary rounded-lg p-2 bg-white w-full">
												</td>
												<td class="w-fit group">
													<input type="checkbox" id="newPrestationInclude" class="hidden peer">
													<label for="newPrestationInclude"
														class="h-max w-full cursor-pointer flex justify-center items-center text-rouge-logo peer-checked:hidden">
														<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
															viewBox="0 0 32 32" fill="none" stroke="currentColor"
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
															class="fill-secondary rounded-lg border border-transparent hover:border-secondary border-box p-1"
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
										class="w-full optionActivite optionVisite optionSpectacle optionParcAttraction hidden">

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
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mardi][ouverture]"
															id="horaires[mardi][ouverture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mercredi][ouverture]"
															id="horaires[mercredi][ouverture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[jeudi][ouverture]"
															id="horaires[jeudi][ouverture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[vendredi][ouverture]"
															id="horaires[vendredi][ouverture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[samedi][ouverture]"
															id="horaires[samedi][ouverture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[dimanche][ouverture]"
															id="horaires[dimanche][ouverture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
												</tr>
												<tr>
													<td>
														Pause
													</td>
													<td class="relative">
														<input type="time" name="horaires[lundi][pause]"
															id="horaires[lundi][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mardi][pause]"
															id="horaires[mardi][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mercredi][pause]"
															id="horaires[mercredi][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[jeudi][pause]"
															id="horaires[jeudi][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[vendredi][pause]"
															id="horaires[vendredi][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[samedi][pause]"
															id="horaires[samedi][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[dimanche][pause]"
															id="horaires[dimanche][pause]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
												</tr>
												<tr>
													<td>
														Reprise
													</td>
													<td class="relative">
														<input type="time" name="horaires[lundi][reprise]"
															id="horaires[lundi][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mardi][reprise]"
															id="horaires[mardi][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mercredi][reprise]"
															id="horaires[mercredi][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[jeudi][reprise]"
															id="horaires[jeudi][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[vendredi][reprise]"
															id="horaires[vendredi][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[samedi][reprise]"
															id="horaires[samedi][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[dimanche][reprise]"
															id="horaires[dimanche][reprise]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
												</tr>
												<tr>
													<td>
														Fermeture
													</td>
													<td class="relative">
														<input type="time" name="horaires[lundi][fermeture]"
															id="horaires[lundi][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mardi][fermeture]"
															id="horaires[mardi][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[mercredi][fermeture]"
															id="horaires[mercredi][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[jeudi][fermeture]"
															id="horaires[jeudi][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[vendredi][fermeture]"
															id="horaires[vendredi][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[samedi][fermeture]"
															id="horaires[samedi][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
													</td>
													<td class="relative">
														<input type="time" name="horaires[dimanche][fermeture]"
															id="horaires[dimanche][fermeture]"
															class="border border-secondary rounded-lg p-2 bg-white mx-auto block">
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
									<div
										class="w-full optionActivite optionVisite optionSpectacle optionParcAttraction hidden">
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
														class="border border-secondary rounded-lg p-2 bg-white w-full">
												</td>
												<td class="w-fit">
													<input type="number" id="newPrixValeur" min="0"
														class="border border-secondary rounded-lg p-2 bg-white">
												</td>
												<td class="w-fit">
													<div class="h-max w-full cursor-pointer flex justify-center items-center"
														id="addPriceButton">
														<svg xmlns="http://www.w3.org/2000/svg"
															class="fill-secondary rounded-lg border border-transparent hover:border-secondary border-box p-1"
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

								<div
									class="optionActivite optionVisite optionSpectacle optionRestauration optionParcAttraction hidden w-full">
									<h1 class="text-h2 text-secondary">Les options</h1>

									<!-- TODO: donner la durée en semaines + la date de lancement -->

									<!-- CGU -->
									<a href="/cgu" class="text-small underline text-secondary"> Voir les CGU</a>

									<!-- Radio button -->
									<div
										class="flex flex-row mb-4 content-center justify-between items-center text-secondary w-full">
										<!-- Sans option -->
										<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
											id="option-rien-div">
											<input type="radio" id="option-rien" name="option" value="option-rien"
												class="hidden" />
											<label for="option-rien">Sans option</label>
										</div>
										<!-- Option en relief -->
										<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
											id="option-relief-div">
											<input type="radio" id="option-relief" name="option" value="option-relief"
												class="hidden" checked="true" />
											<label for="option-relief">En Relief (3.99€)</label>
										</div>
										<!-- À la une -->
										<div class="w-fit p-2 rounded-full border border-transparent hover:border-secondary has-[:checked]:bg-secondary has-[:checked]:text-white font-bold"
											id="option-a-la-une-div">
											<input type="radio" id="option-a-la-une" name="option" class="hidden"
												value="option-a-la-une" />
											<label for="option-a-la-une">À la une (5.99€)</label>
										</div>
									</div>
								</div>
							</div>
							<!-- Créer l'offre -->
							<div
								class="w-full flex justify-center items-center optionActivite optionVisite optionSpectacle optionRestauration optionParcAttraction hidden">
								<input type="submit" value="Créer l'offre" id="submitPart3"
									class="bg-secondary text-white font-medium py-2 px-4 rounded-lg inline-flex items-center border border-transparent hover:bg-secondary/90 hover:border-secondary/90 focus:scale-[0.97] w-1/2 m-1 disabled:bg-gray-300 disabled:border-gray-300"
									disabled="true">
							</div>
						</div>
						<!-- Mettre la preview à droite du fleuve -->
						<div
							class="w-full min-w-[450px] max-w-[450px] h-screen flex justify-center items-center sticky top-0 part1 hidden">
							<div class="h-fit w-full">
								<!-- Affiche de la carte en fonction de l'option choisie et des informations rentrées au préalable. -->
								<!-- Script > listener sur "change" sur les inputs radios (1 sur chaque) ; si input en relief ou À la une, ajouter(.add('active')) à la classlist(.classList) du div {card-preview} "active", sinon l'enlever(.remove('active')) -->
								<div class="card active relative bg-base300 rounded-xl flex flex-col w-full"
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
										document.getElementById("option-rien-div").addEventListener("click", function () {
											toggleRadio("option-rien");
											toggleCardPreview("option-rien");
										});
										document.getElementById("option-relief-div").addEventListener("click", function () {
											toggleRadio("option-relief");
											toggleCardPreview("option-relief");
										});
										document.getElementById("option-a-la-une-div").addEventListener("click", function () {
											toggleRadio("option-a-la-une");
											toggleCardPreview("option-a-la-une");
										});
									</script>
									<!-- En tête -->
									<div
										class="en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg">
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
									<img class="h-48 w-full rounded-t-lg object-cover" src="/public/images/image-test.png"
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
													.addEventListener("input", function () {
														document.getElementById(
															"preview-locality"
														).textContent =
															document.getElementById("locality").value ? document.getElementById("locality").value : document.getElementById("locality").placeholder;
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
													Ajouter un tag...
												</p>
												<script>
													function refreshTagPreview() {
														const tagPreview = document.getElementById(
															"preview-tag-input"
														)

														const tagContainers = document.querySelectorAll('.tag-container');
														tagContainers.forEach(container => {
															if (!container.classList.contains('hidden')) {
																const tags = Array.from(container.children).map(tag => tag.childNodes[0].nodeValue).join(', ');
																tagPreview.textContent = tags !== '' ? (tags.length > 30 ? tags.slice(0, 30) + "..." : tags) : "Ajouter un tag...";
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
				include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
				?>
			</div>
		</div>

		<script src="/scripts/tagManager.js"></script>
		<script src="/scripts/priceManager.js"></script>
		<script src="/scripts/prestationManager.js"></script>
		<script>
			console.log("Before optionToggler.js");
		</script>
		<script src="/scripts/optionToggler.js"></script>
		<script>
			console.log("After optionToggler.js");
		</script>
		<script>
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
			}

			function showPart3() {
				document.getElementById("submitPart3").removeAttribute("disabled");
			}

			function hidePart3() {
				document.getElementById("submitPart3").setAttribute("disabled", "true");
			}

			function checkPart1Validity() {
				const offreRadios = document.querySelectorAll('input[name="type_offre"]');
				let isValid = false;

				offreRadios.forEach((radio) => {
					if (radio.checked) {
						isValid = true;
					}
				});

				if (isValid) {
					showPart1();
				}

				return isValid;
			}

			function checkPart2Validity(fieldChanged) {
				if (!checkPart1Validity()) {
					return false;
				}

				const requiredFields = document.querySelectorAll('.part1 input[required], .part1 textarea[required]');
				let isValid = true;

				requiredFields.forEach((field) => {
					if (field.nodeName === 'INPUT' && field.attributes['type'].value === 'number') { // Locality
						if (field.value === '' || RegExp('^((22)|(29)|(35)|(56))[0-9]{3}$').test(field.value) === false) {
							if (fieldChanged.compareDocumentPosition(field) & Node.DOCUMENT_POSITION_PRECEDING || fieldChanged.compareDocumentPosition(field) === 0) {
								field.classList.remove("border-secondary")
								field.classList.add('border-red-500');
							}
							isValid = false;
						} else {
							field.classList.remove("border-red-500");
							field.classList.add('border-secondary');
						}
					} else {
						if (field.value.trim() === '') {
							if (fieldChanged.compareDocumentPosition(field) & Node.DOCUMENT_POSITION_PRECEDING || fieldChanged.compareDocumentPosition(field) === 0) {
								field.classList.remove("border-secondary")
								field.classList.add('border-red-500');
							}
							isValid = false;
						} else {
							field.classList.remove("border-red-500");
							field.classList.add('border-secondary');
						}
					}
				});

				if (isValid) {
					showPart2();
				}

				return isValid;
			}

			function checkPart3Validity(fieldChanged) {
				if (!checkPart2Validity(fieldChanged)) {
					return false;
				}

				const requiredFields = document.querySelectorAll('.part2 [required]');
				let isValid = true;

				requiredFields.forEach((field) => {
					if (field.nodeName === 'INPUT' && field.attributes['type'].value === 'number') {
						if (fieldChanged.compareDocumentPosition(field) & Node.DOCUMENT_POSITION_PRECEDING || fieldChanged.compareDocumentPosition(field) === 0) {
							if (field.value.trim() === '' || field.value < 0 || RegExp('^[0-9]+$').test(field.value) === false) {
								field.classList.remove("border-secondary")
								field.classList.add('border-red-500');
								isValid = false;
							} else {
								field.classList.remove("border-red-500");
								field.classList.add('border-secondary');
							}
						}
					}
				});

				if (isValid) {
					showPart3();
				} else {
					hidePart3();
				}
			}

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
			// TODO: gérer les horaires
			// TODO: lorsque les informations sont remplies pour lundi, elles sont répétées pour les autres jours
			// TODO: Vérifier que l'horaire d'ouverture soit plus tôt que l'horaire de pause, puis de reprise, puis de fermeture.

			for (const field of ['ouverture', 'pause', 'reprise', 'fermeture']) {
				const lundi = document.getElementById(`horaires[lundi][${field}]`);
				lundi.addEventListener('change', () => {
					for (const jour of ['mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']) {
						const element = document.getElementById(`horaires[${jour}][${field}]`);
						element.value = lundi.value;
					}
				});
			}
		</script>

	<?php } ?>
</body>

</html>