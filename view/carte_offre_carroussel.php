<?php
// FONCTION UTILES

if (!function_exists('chaineVersMot')) {
	function chaineVersMot($str): string
	{
		return str_replace('_', " d'", ucfirst($str));
	}
}

// Obtenir les différentes variables avec les infos nécessaires via des requêtes SQL
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/horaire_controller.php';
$controllerHoraire = new HoraireController();
$horaires = $controllerHoraire->getHorairesOfOffre($id_offre);

foreach ($horaires as $jour => $horaire) {
	$horaires['ouverture'][$jour] = $horaire['ouverture'];
	$horaires['pause_debut'][$jour] = $horaire['pause_debut'];
	$horaires['pause_fin'][$jour] = $horaire['pause_fin'];
	$horaires['fermeture'][$jour] = $horaire['fermeture'];
}
$jour_semaine = date('l');
$jours_semaine_fr = [
	'Monday' => 'lundi',
	'Tuesday' => 'mardi',
	'Wednesday' => 'mercredi',
	'Thursday' => 'jeudi',
	'Friday' => 'vendredi',
	'Saturday' => 'samedi',
	'Sunday' => 'dimanche'
];

$jour_semaine = $jours_semaine_fr[$jour_semaine];
date_default_timezone_set('Europe/Paris');
$heure_actuelle = date('H:i');
$ouvert = false;

foreach ($horaires as $jour => $horaire) {
	if ($jour == $jour_semaine) {
		$ouverture = $horaire['ouverture'];
		$fermeture = $horaire['fermeture'];
		if ($ouverture !== null && $fermeture !== null) {
			if ($fermeture < $ouverture) {
				$fermeture_T = explode(':', $fermeture);
				$fermeture_T[0] = $fermeture_T[0] + 24;
				$fermeture_T = implode(':', $fermeture_T);
			} else {
				$fermeture_T = $fermeture;
			}
			if ($heure_actuelle >= $ouverture && $heure_actuelle <= $fermeture_T) {
				if ($horaire['pause_debut'] !== null && $horaire['pause_fin'] !== null) {
					$pause_debut = $horaire['pause_debut'];
					$pause_fin = $horaire['pause_fin'];
					if ($heure_actuelle >= $pause_debut && $heure_actuelle <= $pause_fin) {
						$ouvert = false;
					} else {
						if ($heure_actuelle >= $ouverture && $heure_actuelle <= $fermeture_T) {
							$ouvert = true;
						}
					}
				} else {
					$ouvert = true;
				}
			}
		}
	}
}

// !!! CARD COMPONENT MEMBER !!!
// Composant dynamique (généré avec les données en php)
// Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
?>
<a class="card <?php if ($option) {
	echo "active ";
} ?> " href='/scripts/go_to_details.php?id_offre=<?php echo $id_offre ?>' <?php echo ($ouvert) ? "title='Ouvert'" : "title='Fermé'"; ?>>

	<div class='w-[30em] h-full relative bg-base100  flex flex-col'>
		<!-- En-tête -->
		<div
			class='en-tete absolute p-4 top-0 w-72 max-w-full bg-blur/75 backdrop-blur left-1/2 -translate-x-1/2 '>
			<h3 class='text-xl text-center '>
				<?php echo $titre_offre; ?>
			</h3>
			<div class='flex w-full justify-between px-2'>
				<p class='text-small'><?php echo $pro['nom_pro'] ?></p>
				<p class='categorie text-small'><?php echo chaineVersMot($categorie_offre) ?></p>
			</div>
		</div>
		<!-- Image de fond -->
		<?php
		require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
		$controllerImage = new ImageController();
		$images = $controllerImage->getImagesOfOffre($id_offre);
		?>
		<img class="h-48 w-full  object-cover" src='/public/images/<?php if ($images['carte']) {
			echo "offres/" . $images['carte'];
		} else {
			echo $categorie_offre . '.jpg';
		} ?>' alt="Image promotionnelle de l'offre">
		<!-- Infos principales -->
		<div class='infos flex items-center justify-around gap-2 px-2 grow'>
			<!-- Localisation -->
			<div class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center min-w-16'>
				<i class='fa-solid fa-location-dot'></i>
				<p class='text-small'><?php
				if (strlen($ville) > 10) {
					echo substr($ville, 0, length: 7) . '...';
				} else {
					echo $ville;
				} ?></p>
				<p class='text-small'><?php echo $code_postal ?></p>
			</div>
			<hr class='h-20 border-black border'>
			<!-- Description avec les tags-->
			<div class='description py-2 flex flex-col gap-2 justify-center self-stretch'>
				<div class='p-1  bg-secondary self-center w-full'>
					<?php
					if ($categorie_offre != 'restauration') {
						require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/tag_offre_controller.php';
						$controllerTagOffre = new TagOffreController();
						$tags_offre = $controllerTagOffre->getTagsByIdOffre($id_offre);

						require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/tag_controller.php';
						$controllerTag = new TagController();
						$tagsAffiche = "";
						$tagsListe = [];
						foreach ($tags_offre as $tag) {
							array_push($tagsListe, $controllerTag->getInfosTag($tag['id_tag']));
						}
						foreach ($tagsListe as $tag) {
							$tagsAffiche .= $tag['nom'] . ', ';
						}

						$tagsAffiche = rtrim($tagsAffiche, ', ');
						if ($tags_offre) {
							?>
							<div class="p-1  bg-secondary self-center w-full">
								<?php
								echo ("<p class='tags text-white text-center overflow-ellipsis line-clamp-1'>$tagsAffiche</p>");
								?>
							</div>
							<?php
						} else {
							?>
							<div class="p-1  bg-secondary self-center w-full">
								<?php
								echo ("<p class='tags text-white text-center overflow-ellipsis line-clamp-1'>Aucun tag à afficher</p>");
								?>
							</div>
							<?php
						}
					} else {
						require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/tag_restaurant_restauration_controller.php';
						$controllerTagRestRestauOffre = new tagRestaurantRestaurationController();
						$tags_offre = $controllerTagRestRestauOffre->getTagsByIdOffre($id_offre);

						require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/tag_restaurant_controller.php';
						$controllerTagRest = new TagRestaurantController();
						$tagsAffiche = "";
						foreach ($tags_offre as $tag) {
							$tagsListe[] = $controllerTagRest->getInfosTagRestaurant($tag['id_tag_restaurant']);
						}
						foreach ($tagsListe as $tag) {
							$tagsAffiche .= $tag[0]['nom'] . ', ';
						}

						$tagsAffiche = rtrim($tagsAffiche, ', ');
						if ($tags_offre) {
						?>
							<div class="p-1  bg-secondary self-center w-full">
								<?php
								echo ("<p class='tags text-white text-center overflow-ellipsis line-clamp-1'>$tagsAffiche</p>");
								?>
							</div>
						<?php
						} else {
						?>
							<div class="p-1  bg-secondary self-center w-full">
								<?php
								echo ("<p class='tags text-white text-center overflow-ellipsis line-clamp-1'>Aucun tag à afficher</p>");
								?>
							</div>
					<?php
						}
					}
					?>
				</div>
				<p class='overflow-hidden line-clamp-2 text-small'>
					<?php echo $resume ?>
				</p>
			</div>
			<hr class='h-20 border-black border'>
			<!-- Notation et Prix -->
			<div class='flex flex-col gap-2 justify-center items-center min-w-16'>
				<?php
				// Moyenne des notes quand il y en a une
				if ($moyenne) {
					$n = $moyenne;
					?>
					<div class="note flex gap-1 flex-wrap" title="<?php echo $moyenne; ?>">
						<?php for ($i = 0; $i < 5; $i++) {
							if ($n > 1) {
								?>
								<img class="w-2" src="/public/icones/oeuf_plein.svg" alt="1 point de note">
								<?php
							} else if ($n > 0) {
								?>
									<img class="w-2" src="/public/icones/oeuf_moitie.svg" alt="0.5 point de note">
								<?php
							} else {
								?>
									<img class="w-2" src="/public/icones/oeuf_vide.svg" alt="0 point de note">
								<?php
							}
							$n--;
						}
						?>
						<!-- <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p> -->
					</div>
					<?php
				}
				?>
				<p class='prix text-small'
					title='<?php echo (chaineVersMot($categorie_offre) !== 'Restauration') ? "Fourchette des prix : Min " . $tarif_min . ", Max " . $tarif_max : "Gamme des prix" ?>'>
					<?php echo $prix_a_afficher ?>
				</p>
			</div>
		</div>
	</div>
</a>