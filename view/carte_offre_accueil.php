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

	<!-- CARTE VERSION TABLETTE -->
	<div class='hidden xl:block w-max h-full relative bg-base100 flex flex-col group'>
		<!-- En-tête -->
		<div
			class='en-tete absolute opacity-0 top-0 w-72 max-w-full bg-blur/50 backdrop-blur left-1/2 -translate-x-1/2 group-hover:opacity-100 duration-200'>
			<h3 class='text-xl text-center  mb-2'>
				<?php echo $titre_offre; ?>
			</h3>
			<div class='flex w-full justify-between px-2'>
				<?php
				// Moyenne des notes quand il y en a une
				if ($moyenne) {
					$n = $moyenne;
					?>
					<div class="note flex gap-1.5 flex-wrap" title="<?php echo $moyenne; ?>">
						<?php for ($i = 0; $i < 5; $i++) {
							if ($n > 1) {
								?>
								<img class="w-3" src="/public/icones/oeuf_plein.svg" alt="1 point de note">
								<?php
							} else if ($n > 0) {
								?>
									<img class="w-3" src="/public/icones/oeuf_moitie.svg" alt="0.5 point de note">
								<?php
							} else {
								?>
									<img class="w-3" src="/public/icones/oeuf_vide.svg" alt="0 point de note">
								<?php
							}
							$n--;
						}
						?>
						<!-- <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p> -->
					</div>
					<?php
				} else { ?>
				<p class='text-lg'><?php echo $pro['nom_pro'] ?></p>
				<?php } ?>
				<p class='text-lg categorie'><?php echo chaineVersMot($categorie_offre) ?></p>
			</div>
		</div>
		<!-- Image de fond -->
		<?php
		require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
		$controllerImage = new ImageController();
		$images = $controllerImage->getImagesOfOffre($id_offre);
		?>
		<img class="h-[27rem] w-full object-cover" src='/public/images/<?php if ($images['carte']) {
																						echo "offres/" . $images['carte'];
																					} else {
																						echo $categorie_offre . '.jpg';
																					} ?>' alt="Image promotionnelle de l'offre">
	</div>

	<!-- CARTE VERSION TÉLÉPHONE -->
	<div class='xl:hidden w-[30em] h-full relative bg-base100  flex flex-col'>
		<!-- En-tête -->
		<div
			class='en-tete absolute top-0 w-72 max-w-full bg-blur/50 backdrop-blur left-1/2 -translate-x-1/2'>
			<h3 class='text-xl text-center  mb-2'>
				<?php echo $titre_offre; ?>
			</h3>
			<div class='flex w-full justify-between px-2'>
				<?php
				// Moyenne des notes quand il y en a une
				if ($moyenne) {
					$n = $moyenne;
					?>
					<div class="note flex gap-1.5 flex-wrap" title="<?php echo $moyenne; ?>">
						<?php for ($i = 0; $i < 5; $i++) {
							if ($n > 1) {
								?>
								<img class="w-3" src="/public/icones/oeuf_plein.svg" alt="1 point de note">
								<?php
							} else if ($n > 0) {
								?>
									<img class="w-3" src="/public/icones/oeuf_moitie.svg" alt="0.5 point de note">
								<?php
							} else {
								?>
									<img class="w-3" src="/public/icones/oeuf_vide.svg" alt="0 point de note">
								<?php
							}
							$n--;
						}
						?>
						<!-- <p class='text-small italic flex items-center'>(<?php echo $nb_avis ?>)</p> -->
					</div>
					<?php
				} else { ?>
				<p class='text-lg '><?php echo $pro['nom_pro'] ?></p>
				<?php } ?>
				<p class='categorie text-lg'><?php echo chaineVersMot($categorie_offre) ?></p>
			</div>
		</div>
		<!-- Image de fond -->
		<?php
		require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
		$controllerImage = new ImageController();
		$images = $controllerImage->getImagesOfOffre($id_offre);
		?>
		<img class="h-72 w-full  object-cover" src='/public/images/<?php if ($images['carte']) {
																				echo "offres/" . $images['carte'];
																			} else {
																				echo $categorie_offre . '.jpg';
																			} ?>' alt="Image promotionnelle de l'offre">
	</div>
</a>