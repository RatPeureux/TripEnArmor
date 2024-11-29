<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">

    <link rel="stylesheet" href="/styles/input.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="/scripts/loadCaroussel.js" type="module"></script>

    <!-- Pour les requêtes AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Détails d'une offre - PACT</title>
</head>

<body class="flex flex-col">

    <!-- Inclusion du header -->
    <?php
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header.php';
    ?>

    <?php
    $id_offre = $_SESSION['id_offre'];
    if (isset($_SESSION['id_membre'])) {
        $id_membre = $_SESSION['id_membre'];
    }

    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Avoir une variable $pro qui contient les informations du pro actuel.
    $stmt = $dbh->prepare("SELECT id_pro FROM sae_db._offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $id_pro = $stmt->fetch(PDO::FETCH_ASSOC)['id_pro'];

    $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE id_compte = :id_pro");
    $stmt->bindParam(':id_pro', $id_pro);
    $stmt->execute();
    $pro = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($pro) {
        $nom_pro = $pro['nom_pro'];
    }

    // Obtenir l'ensemble des informations de l'offre
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';
    switch ($categorie_offre) {
        case 'restauration':
            // appel controlller restauration
            // $restaurtion egal Ctrl->getRestaurationById($id_offre)
            // echo $restauration['id_repas']
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';
            $controllerRestauration = new RestaurationController();
            $parc_attraction = $controllerRestauration->getInfosRestauration($id_offre);
            break;

        case 'activite':
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/activite_controller.php';
            $controllerActivite = new ActiviteController();
            $activite = $controllerActivite->getInfosActivite($id_offre);

            // Durée de l'activité
            $duree_act = $activite['duree'];
            $duree_act = substr($duree_act, 0, -3);
            $duree_act = str_replace(':', 'h', $duree_act);

            // Prestations de l'activité
            $prestation = $activite['prestations'];

            // Âge requis pour l'activité
            $age_requis_act = $activite['age_requis'];
            break;

        case 'parc_attraction':
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/parc_attraction_controller.php';
            $controllerParcAttraction = new ParcAttractionController();
            $parc_attraction = $controllerParcAttraction->getInfosParcAttraction($id_offre);

            // Âge requis pour le parc d'attraction
            $age_requis_pa = $parc_attraction['age_requis'];

            // Nombre d'attractions du parc d'attraction
            $nb_attractions = $parc_attraction['nb_attractions'];
            break;

        case 'visite':
            require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/visite_controller.php';
            $controllerVisite = new VisiteController();
            $visite = $controllerVisite->getInfosVisite($id_offre);

            // Durée de la visite
            $duree_vis = $visite['duree'];
            $duree_vis = substr($duree_vis, 0, -3);
            $duree_vis = str_replace(':', 'h', $duree_vis);

            // Visite guidée ou non
            $guideBool = $visite['avec_guide'];
            if ($guideBool == true) {
                $guide = 'oui';
                require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/visite_langue_controller.php';
                $controllerLangue = new VisiteLangueController();
                $tabLangues = $controllerLangue->getLanguesByIdVisite($id_offre);
                $langues = '';
                foreach ($tabLangues as $langue) {
                    // Ajout des langues parlées lors de la visite
                    $langues .= $langue['nom'] . ', ';
                }
                $langues = rtrim($langues, ', ');
            } else {
                $guide = 'non';
            }

            break;

        case 'spectacle':
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/spectacle_controller.php';
            $controllerSpectacle = new SpectacleController();
            $spectacle = $controllerSpectacle->getInfosSpectacle($id_offre);

            // Durée du spectacle
            $duree_spec = $spectacle['duree'];
            $duree_spec = substr($duree_spec, 0, -3);
            $duree_spec = str_replace(':', 'h', $duree_spec);

            // Capacité du spectacle
            $capacite = $spectacle['capacite'];

            break;

        default:
            break;
    }

    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/horaire_controller.php';
    $controllerHoraire = new HoraireController();

    // VALEUR TEST CAR PAS DANS LA BDD
    $horairesV1 = [
        "lundi" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ],
        "mardi" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ],
        "mercredi" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ],
        "jeudi" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ],
        "vendredi" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ],
        "samedi" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ],
        "dimanche" => [
            "ouverture" => "08:00",
            "pause_debut" => "12:00",
            "pause_fin" => "14:00",
            "fermeture" => "18:00",
        ]
    ]; # $controllerHoraire->getHorairesOfOffre($id_offre);
    
    $horaires = [];

    foreach ($horairesV1 as $jour => $horaire) {
        $horaires['ouverture'][$jour] = $horaire['ouverture'];
        $horaires['pause_debut'][$jour] = $horaire['pause_debut'];
        $horaires['pause_fin'][$jour] = $horaire['pause_fin'];
        $horaires['fermeture'][$jour] = $horaire['fermeture'];
    }
    if ($categorie_offre !== 'restauration') {
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tarif_public_controller.php';
        $controllerGrilleTarifaire = new TarifPublicController();
        // VALEUR TEST CAR PAS DANS LA BDD
        $tarifs = [
            [
                "titre_tarif" => "Tarif adulte",
                "prix" => 10
            ],
            [
                "titre_tarif" => "Tarif enfant",
                "prix" => 5
            ]
        ];
    }

    if ($categorie_offre == 'parc_attraction') {
        // require dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/t_image_img_controller.php';
        // $controllerImage = new TImageImgController();
        // $path_plan = $controllerImage->getPathToPlan($id_offre);
    }
    ?>

    <main class="flex flex-col md:block md:mx-10 self-center rounded-lg md:p-2 max-w-[1280px] overflow-auto">
        <div class="flex md:gap-3">
            <!-- PARTIE GAUCHE (menu) -->
            <div>
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu.php';
                ?>
            </div>

            <!-- PARTIE DROITE (offre & détails) -->
            <div class="grow md:p-4 flex flex-col items-center md:gap-4">

                <!-- CAROUSSEL -->
                <div
                    class="w-full h-80 md:h-[400px] overflow-hidden relative swiper default-carousel swiper-container md:border md:border-black md:rounded-lg">
                    <!-- Wrapper -->
                    <?php
                    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
                    $controllerImage = new ImageController();
                    $images = $controllerImage->getImagesOfOffre($id_offre);
                    ?>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src='/public/images/<?php if ($images['carte']) {
                                echo $images['carte'];
                            } else {
                                echo $categorie_offre . '.jpg';
                            } ?>' alt="image de slider">
                        </div>
                        <?php
                        if ($images['details']) {
                            foreach ($images['details'] as $image) {
                                ?>
                                <div class="swiper-slide !w-full">
                                    <img class="object-cover w-full h-full" src='/public/images/<?php echo $image; ?>'
                                        alt="image de slider">
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- Boutons de navigation sur la slider -->
                    <div class="flex items-center gap-8 justify-center">
                        <a
                            class="swiper-button-prev group flex justify-center items-center border border-solid rounded-full !top-1/2 -translate-y-1/2 !left-5 !bg-primary !text-white after:!text-base">
                        </a>
                        <a
                            class="swiper-button-next group flex justify-center items-center border border-solid rounded-full !top-1/2 -translate-y-1/2 !right-5 !bg-primary !text-white after:!text-base">
                        </a>
                    </div>
                    <a href="#" onclick="history.back()"
                        class="border absolute top-2 left-2 z-20 p-2 bg-bgBlur/75 rounded-lg flex justify-center items-center"><i
                            class="fa-solid fa-arrow-left text-h1"></i></a>
                    <div class="swiper-pagination"></div>
                </div>

                <!-- RESTE DES INFORMATIONS SUR L'OFFRE -->
                <div class="flex flex-col gap-5">
                    <div class="flex flex-row items-center">
                        <h1 class="text-h1 font-bold"><?php echo $offre['titre'] ?></h1>
                        <p class="professionnel text-h1">&nbsp;- <?php echo $nom_pro ?></p>
                    </div>
                    <!-- Afficher les tags de l'offre -->
                    <p class="text-small">
                        <?php echo $resume ?>
                    </p>

                    <?php
                    if ($tags) {
                        ?>
                        <div class="p-1 rounded-lg bg-secondary self-center w-full">
                            <?php
                            echo ("<p class='text-white text-center'>$tags</p>");
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="p-1 rounded-lg bg-secondary self-center w-full">
                            <?php
                            echo ("<p class='text-white text-center'>Aucun tag à afficher</p>");
                            ?>
                        </div>
                        <?php
                    }
                    ?>


                    <!-- Partie du bas de la page (toutes les infos pratiques) -->
                    <div class="flex flex-row gap-4">
                        <!-- Partie description -->
                        <div class="partie-description flex flex-col basis-1/2">
                            <!-- Prix + localisation -->
                            <div class="flex flex-col space-y-2 md:gap-4">
                                <p class="text-h4 font-bold">À propos</p>
                                <div class="flex items-center gap-4 px-2">
                                    <i class="w-6 text-center fa-solid fa-location-dot"></i>
                                    <div class="text-small">
                                        <p><?php echo $ville . ', ' . $code_postal ?></p>
                                        <p><?php echo $adresse['numero'] . ' ' . $adresse['odonyme'] . ' ' . $adresse['complement'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center px-2 gap-4">
                                    <i class="w-6 text-center fa-solid fa-money-bill"></i>
                                    <p class="prix text-small mt-1"><?php echo $prix_a_afficher ?></p>
                                </div>
                            </div>
                            <!-- Description détaillée -->
                            <div class="description flex flex-col my-4">
                                <p class="text-justify text-small px-2">
                                    <?php echo $description ?>
                                </p>
                            </div>
                        </div>

                        <!-- Partie avis & Infos en fonction du type offre -->
                        <div class="basis-1/2">
                            <!-- Infos en fonction du type de l'offre -->
                            <a href="" class="">
                                <div class="flex flex-row justify-between" id="horaire-button">
                                    <p class="text-h4 font-bold">Horaires</p>
                                    <p id="horaire-arrow">></p>
                                </div>
                                <div class="hidden text-small py-3" id="horaire-info">
                                    <table class="w-full table-auto">
                                        <thead>
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
                                            <?php
                                            foreach ($horaires as $etat => $jours) {
                                                ?>
                                                <tr><?php
                                                foreach ($jours as $jour => $value) {
                                                    ?>
                                                        <td class="relative">
                                                            <p class="text-center">
                                                                <?php
                                                                echo $value;
                                                                ?>
                                                            </p>
                                                        </td>
                                                        <?php
                                                }
                                                ?>
                                                </tr><?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </a>
                            <a href="" class="">
                                <div class="flex flex-row justify-between pt-3" id="compl-button">
                                    <p class="text-h4 font-bold">Informations complémentaires</p>
                                    <p id="compl-arrow">></p>
                                </div>
                                <div class="flex flex-col py-3 hidden" id="compl-info">
                                    <?php
                                    switch ($categorie_offre) { # TODO: faire plusieurs if plutot que des switch
                                        case 'restauration':
                                            // VALEUR TEST CAR PAS DANS LA BDD
                                            $tags_type_repas = 'Petit-dej, Brunch, Déjeuner, Dîner, Goûter';
                                            ?>
                                            <div class="text-small flex flex-row">
                                                <p class="text-small">Repas servis&nbsp:&nbsp</p>
                                                <p><?php echo $tags_type_repas ?></p>
                                            </div>
                                            <?php
                                            break;

                                        case 'activite':
                                            ?>
                                            <div class="text-small flex flex-row">
                                                <p>Durée&nbsp:&nbsp</p>
                                                <p><?php echo $duree_act ?></p>
                                            </div>
                                            <p class="text-small">Âge requis <?php echo $age_requis_act ?> ans</p>
                                            <div class="text-small">
                                                <?php echo $prestation ?>
                                            </div>

                                            <?php
                                            break;

                                        case 'parc_attraction':
                                            ?>
                                            <div class="text-small flex flex-row">
                                                <p>Âge requis&nbsp:&nbsp</p>
                                                <p><?php echo $age_requis_pa ?></p>
                                                <p>&nbspans</p>
                                            </div>
                                            <div class="text-small flex flex-row">
                                                <p>Nombre d'attraction&nbsp:&nbsp</p>
                                                <p><?php echo $nb_attractions ?></p>
                                            </div>
                                            <?php
                                            if ($images) {
                                                ?>
                                                <img src="/public/images/<?php echo $images['plan']; ?>" alt="">
                                                <?php
                                            } else {
                                                ?>
                                                <p class="text-small">Aucun plan</p>
                                                <?php
                                            } ?>
                                            <?php
                                            break;

                                        case 'visite':
                                            ?>
                                            <div class="text-small flex flex-row">
                                                <p>Durée&nbsp:&nbsp</p>
                                                <p><?php echo $duree_vis ?></p>
                                            </div>
                                            <div class="text-small flex flex-row">
                                                <p>Visite guidée :&nbsp</p>
                                                <p><?php echo $guide ?></p>
                                            </div>
                                            <div class="text-small">
                                                <p>Langue(s) parlée(s) lors de la visite guidée :&nbsp <?php echo $langues ?>
                                                </p>
                                            </div>
                                            <?php
                                            break;

                                        case 'spectacle':
                                            ?>
                                            <div class="text-small flex flex-row">
                                                <p>Durée&nbsp:&nbsp</p>
                                                <p><?php echo $duree_spec ?></p>
                                            </div>
                                            <div class="text-small flex flex-row">
                                                <p>Capacité :&nbsp</p>
                                                <p><?php echo $capacite ?></p>
                                                <p>&nbsppersonnes</p>
                                            </div>
                                            <?php
                                            break;

                                        default:
                                            ?>
                                            <p class="text-small">Aucune informations complémentaires à afficher.</p>
                                            <?php
                                            break;
                                    }
                                    ?>
                                </div>
                            </a>
                            <?php
                            if ($categorie_offre != 'restauration') {
                                ?>
                                <a href="" class="">
                                    <div class="flex flex-row justify-between pt-3" id="grille-button">
                                        <p class="text-h4 font-bold">Grille tarifaire</p>
                                        <p id="grille-arrow">></p>
                                    </div>
                                    <div class="hidden text-small py-3" id="grille-info">
                                        <table class="">
                                            <tbody>
                                                <?php
                                                foreach ($tarifs as $tarif) {
                                                    ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo $tarif['titre_tarif'] ?> :
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $tarif['prix'] ?> €
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </a>
                                <?php
                            }
                            ?>

                            <!-- Partie avis -->
                            <div class="mt-5 flex flex-col gap-2">

                                <h3 class="text-h4 font-bold">Avis</h3>

                                <?php
                                if (isset($_SESSION['id_membre'])) {
                                    // UTILISATEUR CONNECTÉ, 2 cas :
                                    // - a déjà écrit un avis, auquel cas on le voit en premier et on peut le modifier
                                    // - n'a pas déjà écrit d'avis, auquel cas un formulaire de création d'avis apparaît
                                
                                    // vérifier si l'utilisateur a écrit un avis
                                    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
                                    $avisController = new AvisController;
                                    $mon_avis = $avisController->getAvisByIdMembreEtOffre($_SESSION['id_membre'], $id_offre);
                                    if ($mon_avis) { ?>
                                        <!-- AFFICHER SON AVIS ET POUVOIR LE MODIFIER -->
                                        <?php
                                        $id_avis = $mon_avis['id_avis'];
                                        $id_membre = $_SESSION['id_membre'];
                                        include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/mon_avis_view.php';
                                        ?>
                                    <?php } else {
                                        ?>
                                        <!-- FORMULAIRE DE CRÉATION D'AVIS -->
                                        <div class="flex flex-col gap-2">
                                            <button
                                                onclick="document.getElementById('avis_formulaire').classList.toggle('hidden');"
                                                class="bg-secondary font-bold text-white rounded-lg p-2 self-end flex items-center gap-2">
                                                <i class="fa-solid fa-pen"></i>
                                                <p>Rédiger un avis</p>
                                            </button>

                                            <form id="avis_formulaire" action="/scripts/creation_avis.php" method="POST"
                                                class="hidden flex flex-col gap-4">

                                                <!-- Titre de l'avis -->
                                                <div>
                                                    <label for="titre">Titre</label>
                                                    <input type="text" name="titre" placeholder="Titre de l'avis"
                                                        class="w-full border border-black rounded-lg p-1" required>
                                                </div>

                                                <!-- Commentaire de l'avis -->
                                                <textarea type="commentaire" name="commentaire" placeholder="Votre commentaire"
                                                    class="w-full border border-black rounded-lg p-1"></textarea>

                                                <!-- Note globale donnée (pour toutes les offres) -->
                                                <div>
                                                    <label for="note_globale">Note globale</label>
                                                    <select name="note_globale" id="note_globale" class="p-1 rounded-lg"
                                                        required>
                                                        <option selected disabled>...</option>
                                                        <option value="0">0</option>
                                                        <option value="0.5">0,5</option>
                                                        <option value="1">1</option>
                                                        <option value="1.5">1,5</option>
                                                        <option value="2">2</option>
                                                        <option value="2.5">2,5</option>
                                                        <option value="3">3</option>
                                                        <option value="3.5">3,5</option>
                                                        <option value="4">4</option>
                                                        <option value="4.5">4,5</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>

                                                <!-- Date de l'expérience -->
                                                <div>
                                                    <label for="date_experience">Date de l'expérience</label>
                                                    <input type="date" name="date_experience" id="date_experience"
                                                        value="Date de votre expérience" required>
                                                </div>

                                                <!-- Contexte de passage -->
                                                <div>
                                                    <label for="contexte_passage">Contexte de passage</label>
                                                    <select name="contexte_passage" id="contexte_passage" class="p-1 rounded-lg"
                                                        required>
                                                        <option selected disabled>...</option>
                                                        <option value="en solo">en solo</option>
                                                        <option value="en couple">en couple</option>
                                                        <option value="entre amis">entre amis</option>
                                                        <option value="pour le travail">pour le travail</option>
                                                        <option value="en famille">en famille</option>
                                                    </select>
                                                </div>

                                                <!-- Publier l'avis -->
                                                <input type="submit" value="+ Publier"
                                                    class="bg-secondary text-white font-bold rounded-lg p-2 self-end">

                                                <hr class="w-1/2 border border-black self-end my-2 rounded-lg bg-black">

                                                <!-- Champs cachés pour transmettre des donées à la création de l'offre -->
                                                <input type="text" id='id_offre' name='id_offre' hidden
                                                    value="<?php echo $_SESSION['id_offre'] ?>">
                                                <input type="text" id='id_membre' name='id_membre' hidden
                                                    value="<?php echo $_SESSION['id_membre'] ?>">
                                            </form>

                                            <script>
                                                // Eviter de pouvoir sélectionner un date ultérieure au jour actuel
                                                function setMaxDate() {
                                                    const today = new Date();
                                                    const year = today.getFullYear();
                                                    const month = String(today.getMonth() + 1).padStart(2, '0');
                                                    const day = String(today.getDate()).padStart(2, '0');
                                                    const maxDate = `${year}-${month}-${day}`;

                                                    document.getElementById("date_experience").setAttribute("max", maxDate);
                                                }

                                                // Call the function when the page loads
                                                window.onload = setMaxDate;
                                            </script>

                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    // UTILISATEUR PAS CONNECTÉ
                                } else { ?>
                                    <p class="text-small italic">Connectez-vous pour rédiger un avis</p>
                                    <?php
                                }
                                ?>

                                <!-- Conteneur pour tous les avis -->
                                <div id="avis-container" class="flex flex-col gap-2 items-center"></div>

                                <!-- Bouton pour charger plus d'avis -->
                                <div class="flex gap-2 items-center justify-center self-end">
                                    <!-- Symbole de chargement quand les avis chargent -->
                                    <img id="loading-indicator" class="w-8 h-6" style="display: none;"
                                        src="/public/images/loading.gif" alt="Loading...">
                                    <button class="text-small font-bold" id="load-more-btn">
                                        Afficher plus...
                                    </button>
                                </div>

                            </div>

                            <!-- A garder ici car il y a du PHP -->
                            <script>
                                $(document).ready(function () {
                                    // Paramètres à passer au fichier PHP de chargement des avis
                                    let idx_avis = 0;
                                    const id_offre = <?php echo $_SESSION['id_offre'] ?>;
                                    const id_membre = <?php if (isset($_SESSION['id_membre'])) {
                                        echo $_SESSION['id_membre'];
                                    } else {
                                        echo '-1';
                                    } ?>;

                                    // Charger les X premiers avis
                                    loadAvis();

                                    // Ajouter des avis quand le bouton est cliqué
                                    $('#load-more-btn').click(function () {
                                        loadAvis();
                                    });

                                    // Fonction pour charger X avis (en PHP), puis les ajouter à la page via AJAX JS
                                    function loadAvis() {
                                        // Afficher le loader pendant le chargement
                                        $('#loading-indicator').show();

                                        // Désactiver le bouton pendant le chargement
                                        $('#load-more-btn').prop('disabled', true);

                                        $.ajax({
                                            url: '/scripts/load_avis.php',
                                            type: 'GET',
                                            data: {
                                                id_offre: id_offre,
                                                idx_avis: idx_avis,
                                                id_membre: id_membre
                                            },

                                            // Durant l'exécution de la requête
                                            success: function (response) {
                                                const lesAvisCharges = response;
                                                if (lesAvisCharges.length > 0) {
                                                    // Ajouter le contenu HTML généré par loaded avis.
                                                    $('#avis-container').append(lesAvisCharges);

                                                    // Pour l'éventuel prochain chargement, incrémenter le curseur
                                                    idx_avis += 3;
                                                } else {
                                                    // Ne plus pouvoir cliquer sur le bouton quand il n'y a plus d'avis
                                                    $('#load-more-btn').prop('disabled', true).text('');
                                                }
                                            },

                                            // A la fin, chacher le logo de chargement
                                            complete: function () {
                                                // Masquer le loader après la requête
                                                $('#loading-indicator').hide();
                                                // Réactiver le bouton après la requête (que ce soit réussi ou non)
                                                $('#load-more-btn').prop('disabled', false);
                                            }
                                        });
                                    }
                                });
                            </script>

                            <?php
                            // include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
                            // $avisController = new AvisController;
                            
                            // // Test d'insertion d'un avis (OK)
                            // $maDate = date('2024-11-02 10:10:10');
                            // $avisController->createAvis("monTitre", "c nul", $maDate, $id_membre, $id_offre);
                            // print_r($avisController->getAvisByIdOffre($id_offre));
                            ?>

                        </div>
                    </div>
                </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>

    <script>
        // Configurer les flèches pour faire des dropdown menu stylés
        function setupToggle(arrowID, buttonID, infoID) {
            const button = document.getElementById(buttonID);
            const arrow = document.getElementById(arrowID);
            const info = document.getElementById(infoID);

            if (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    arrow.classList.toggle('rotate-90');
                    info.classList.toggle('hidden');
                });
            }
        }
        setupToggle('horaire-arrow', 'horaire-button', 'horaire-info');
        setupToggle('compl-arrow', 'compl-button', 'compl-info');
        setupToggle('grille-arrow', 'grille-button', 'grille-info');
    </script>
</body>

</html>