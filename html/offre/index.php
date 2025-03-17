<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../php_files/authentification.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails d'une offre - PACT</title>

    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/styles/style.css">

    <script type="module" src="/scripts/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="/scripts/loadCaroussel.js" type="module"></script>

    <!-- Pour les requêtes ajax -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

</head>

<body class="flex flex-col">

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

    require_once dirname(path: $_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_prive_controller.php";
    $result = [
        "id_compte" => "",
        "nom_pro" => "",
        "email" => "",
        "tel" => "",
        "id_adresse" => "",
        "data" => []
    ];
    $proController = new ProPriveController();

    $proAuth = $proController->getInfosProPrive($pro['id_compte']);
    if (!$proAuth) {
        require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_public_controller.php";
        $proController = new ProPublicController();
        $proAuth = $proController->getInfosProPublic($pro['id_compte']);

        $result["id_compte"] = $proAuth["id_compte"];
        $result["nom_pro"] = $proAuth["nom_pro"];
        $result["email"] = $proAuth["email"];
        $result["tel"] = $proAuth["num_tel"];
        $result["id_adresse"] = $proAuth["id_adresse"];
        $result["data"]["type_orga"] = $proAuth["type_orga"];
        $result["data"]["type"] = "public";
    } else {
        $result["id_compte"] = $proAuth["id_compte"];
        $result["nom_pro"] = $proAuth["nom_pro"];
        $result["email"] = $proAuth["email"];
        $result["tel"] = $proAuth["tel"];
        $result["id_adresse"] = $proAuth["id_adresse"];
        $result["data"]["numero_siren"] = $proAuth["num_siren"];
        $result["data"]["id_rib"] = $proAuth["id_rib"];
        $result["data"]["type"] = "prive";
    }

    // Vérifier si on est connecté avec le compte du pro qui peut répondre
    $pro_can_answer = false;
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    $stmt = $dbh->prepare("SELECT id_pro FROM sae_db._offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $id_pro_must_have = $stmt->fetch(PDO::FETCH_ASSOC)['id_pro'];
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
    $pro_can_answer = (isConnectedAsPro() && $id_pro_must_have == $_SESSION['id_pro']) ? true : false;

    // Possiblité de blacklister : type_offre = premium, tickets blacklistage restants et pro_can_answer
    $pro_can_blacklist = false;
    $stmt = $dbh->prepare(
        "
        SELECT * FROM sae_db._avis
        JOIN sae_db._offre ON sae_db._offre.id_offre = sae_db._avis.id_offre
        WHERE sae_db._offre.id_offre = :id_offre;
    "
    );
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $id_type_offre = $stmt->fetch(PDO::FETCH_ASSOC)['id_type_offre'];

    $stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_blacklistes_en_cours WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $nb_blacklistes_en_cours = $stmt->rowCount();
    if ($stmt->rowCount() < 3 && $pro_can_answer && $id_type_offre == '2') {
        $pro_can_blacklist = true;
    }

    // Obtenir l'ensemble des informations de l'offre
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_offre = :id_offre");
    if (isset($_GET['détails']) && $_GET['détails'] !== '') {
        $stmt->bindParam(':id_offre', $_GET['détails']);
    } else {
        header('location: /404');
        exit();
    }

    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($offre)) {
        header('location: /404');
        exit();
    }

    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php'; ?>

    <!-- Inclusion du header -->
    <?php
    require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/view/header.php';

    switch ($categorie_offre) {
        case 'restauration':

            require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';
            $controllerRestauration = new RestaurationController();
            $restauration = $controllerRestauration->getInfosRestauration($id_offre);

            // Type de repas servis
            require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_type_repas_controller.php';
            $controllerRestaurationType = new RestaurationTypeRepasController();
            $restaurationListeId = $controllerRestaurationType->getTypesRepasBydIdRestaurant($id_offre);

            require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/type_repas_controller.php';
            $controllerTypeRepas = new TypeRepasController();
            $tags_type_repas = '';
            foreach ($restaurationListeId as $type) {
                $type_repas[] = $controllerTypeRepas->getInfoTypeRepas($type['id_type_repas']);
            }
            foreach ($type_repas as $type_repa) {
                $tags_type_repas .= $type_repa['nom'] . ', ';
            }
            $tags_type_repas = rtrim($tags_type_repas, ', ');

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
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/activite_prestation_controller.php';
            $controllerActivitePrestation = new ActivitePrestationController();
            $activitePrestations = $controllerActivitePrestation->getPrestationsByIdActivite($id_offre);

            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/prestation_controller.php';
            $controllerPrestation = new PrestationController();
            foreach ($activitePrestations as $prestation) {
                $prestations[] = $controllerPrestation->getPrestationById($prestation['id_prestation']);
            }

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
    $horaires = $controllerHoraire->getHorairesOfOffre($id_offre);

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

    if ($categorie_offre !== 'restauration') {
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tarif_public_controller.php';
        $controllerGrilleTarifaire = new TarifPublicController();
    }
    ?>

    <main class="w-full grow flex items-start justify-center p-2">
        <div class="flex justify-center w-full md:max-w-[1280px]">

            <!-- PARTIE GAUCHE (menu) -->
            <div id="menu">
                <?php
                if (!isConnectedAsPro()) {
                    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/menu.php';
                }
                ?>
            </div>

            <!-- PARTIE DROITE (offre & détails) -->
            <div class="grow md:p-4 flex flex-col items-center md:gap-4">

                <div
                    class="flex flex-col w-full space-y-4 md:space-y-0 md:flex-row md:justify-between md:items-start md:space-x-4">
                    <!-- CAROUSSEL -->
                    <div
                        class="w-2/3 h-80 md:h-[400px] overflow-hidden relative swiper default-carousel swiper-container">
                        <!-- Wrapper -->
                        <?php
                        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/image_controller.php';
                        $controllerImage = new ImageController();
                        $images = $controllerImage->getImagesOfOffre($id_offre);
                        ?>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide !w-full">
                                <img class="object-cover w-full h-full" src='/public/images/<?php if ($images['carte']) {
                                    echo "offres/" . $images['carte'];
                                } else {
                                    echo $categorie_offre . '.jpg';
                                } ?>' alt="Image de slider">
                            </div>
                            <?php
                            if ($images['details']) {
                                foreach ($images['details'] as $image) {
                                    ?>
                                    <div class="swiper-slide !w-full">
                                        <img class="object-cover w-full h-full"
                                            src='/public/images/<?php echo "offres/" . $image; ?>' alt="Image de slider">
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>

                        <!-- Pagination en bas du slider -->
                        <div class="swiper-pagination"></div>

                        <!-- Boutons de navigation sur la slider -->
                        <?php if ($images['details']) { ?>
                            <div class="flex items-center gap-8 justify-center">
                                <a
                                    class="swiper-button-prev group flex justify-center items-center !top-1/2 !left-5 !bg-primary !text-white after:!text-base">
                                    ‹</a>
                                <a
                                    class="swiper-button-next group flex justify-center items-center !top-1/2 !right-5 !bg-primary !text-white after:!text-base">
                                    ›</a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div id="map" class="w-full md:w-1/3 h-[400px] border border-gray-300"></div>
                    <?php
                    // Connexion à la BDD
                    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

                    // Récupérer l'ID de l'offre depuis l'URL
                    $id_offre_map = isset($_GET['détails']) ? (int) $_GET['détails'] : 0;

                    // Récupérer les détails de l'offre
                    $stmt = $dbh->prepare("
                        SELECT o.*, a.lat, a.lng
                        FROM sae_db._offre o
                        JOIN sae_db._adresse a ON o.id_adresse = a.id_adresse
                        WHERE o.id_offre = :id_offre
                    ");
                    $stmt->bindParam(':id_offre', $id_offre_map);
                    $stmt->execute();
                    $offre_adresse_map = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <script>
                        window.mapConfig = {
                            center: [<?= $offre_adresse_map['lat'] ?? '48.5' ?>, <?= $offre_adresse_map['lng'] ?? '-2.5' ?>], // Coordonnées de l'offre
                            zoom: 16, // Zoom plus proche
                            selectedOffer: {
                                id: <?= $offre_adresse_map['id_offre'] ?>,
                                name: "<?= addslashes($offre_adresse_map['titre']) ?>",
                                lat: <?= $offre_adresse_map['lat'] ?? '0' ?>,
                                lng: <?= $offre_adresse_map['lng'] ?? '0' ?>
                            }
                        };
                    </script>
                    <script src="/scripts/map.js"></script>
                </div>


                <!-- RESTE DES INFORMATIONS SUR L'OFFRE -->
                <div class="space-y-2 px-2 md:px-0 w-full">
                    <div class="flex flex-col justify-between md:flex-row w-full">
                        <div class="flex flex-col md:flex-row w-fit">
                            <h1 class="text-3xl "><?php echo $offre['titre'] ?></h1>
                            <p class="hidden text-3xl md:flex">&nbsp;-&nbsp;</p>
                            <p class="professionnel text-3xl"><?php echo $nom_pro ?></p>
                        </div>
                        <?php
                        // Moyenne des notes quand il y en a une
                        if (isset($moyenne) && 0 <= $moyenne && $moyenne <= 5) {
                            $n = $moyenne ?>
                            <div class="flex gap-1">
                                <div class="flex gap-1 shrink-0">
                                    <?php for ($i = 0; $i < 5; $i++) {
                                        if ($n >= 1) {
                                            ?>
                                            <img class="w-4" src="/public/icones/egg-full.svg" alt="1 point de note">
                                            <?php
                                        } else if ($n > 0) {
                                            ?>
                                                <img class="w-4" src="/public/icones/egg-half.svg" alt="0.5 point de note">
                                            <?php
                                        } else {
                                            ?>
                                                <img class="w-4" src="/public/icones/egg-empty.svg" alt="0 point de note">
                                            <?php
                                        }
                                        $n--;
                                    }
                                    ?>
                                </div>
                                <p class='text-sm flex px-1.5 pt-1.5 items-center'>
                                    <?php echo number_format($moyenne, 1, ',', '') ?>
                                </p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php if ($ouvert == true) {
                        ?>
                        <p class="text-xl  text-green-500">Ouvert</p>
                        <?php
                    } else {
                        ?>
                        <p class="text-xl  text-red-500">Fermé</p>
                        <?php
                    }
                    ?>
                    <div class="w-full">
                        <p class="text-sm">
                            <?php echo $resume ?>
                        </p>
                    </div>

                    <!-- Afficher les tags de l'offre -->
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
                        if ($tags_offre) {
                            foreach ($tags_offre as $tag) {
                                $tagsListe[] = $controllerTagRest->getInfosTagRestaurant($tag['id_tag_restaurant']);
                            }
                            foreach ($tagsListe as $tag) {
                                $tagsAffiche .= $tag[0]['nom'] . ', ';
                            }
                        }
                        if ($tags_offre) {
                            $tagsAffiche = rtrim($tagsAffiche, ', ');
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

                    <!-- Partie du bas de la page (toutes les infos pratiques) -->
                    <div class="flex flex-col md:flex-row w-full">
                        <!-- Partie description -->
                        <div class="partie-description flex flex-col basis-1/2 pr-2">
                            <!-- Prix + localisation -->
                            <div class="flex flex-col space-y-2 md:gap-4">
                                <p class="text-lg ">À propos</p>
                                <div class="flex items-center gap-4 px-2">
                                    <i class="w-6 text-center fa-solid fa-location-dot"></i>
                                    <div class="text-sm">
                                        <p><?php echo $ville . ', ' . $code_postal ?></p>
                                        <p>
                                            <?php
                                            echo $adresse['numero'] . ' ' . $adresse['odonyme'] . ' ' . $adresse['complement']
                                                ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center px-2 gap-4">
                                    <i class="w-6 text-center fa-solid fa-money-bill"></i>
                                    <p class="prix text-sm mt-1"><?php echo $prix_a_afficher ?></p>
                                </div>
                            </div>
                            <!-- Description détaillée -->
                            <div class="description flex flex-col space-y-2 my-4">
                                <p class="text-lg ">Description</p>
                                <p class="text-justify text-sm px-2 prose">
                                    <?php echo $description ?>
                                </p>
                            </div>
                        </div>

                        <!-- Partie avis & Infos en fonction du type offre -->
                        <div class="basis-1/2">
                            <!-- Infos en fonction du type de l'offre -->
                            <a class="">
                                <div class="flex flex-row justify-between" id="horaire-button">
                                    <div class="flex ">
                                        <p class="text-lg ">Horaires&nbsp;</p>
                                    </div>
                                    <p id="horaire-arrow">></p>
                                </div>
                                <div class="text-sm py-3 px-2" id="horaire-info">
                                    <?php
                                    foreach ($horaires as $jour => $horaire) {
                                        echo "$jour : ";
                                        foreach ($horaire as $key => $value) {
                                            if ($value !== null) {
                                                $horaire[$key] = substr($value, 0, -3);
                                            }
                                        }
                                        if (!isset($horaire['ouverture'])) {
                                            echo "Fermé <br>";
                                        } else {
                                            if (!isset($horaire['pause_debut'])) {
                                                echo $horaire['ouverture'] . ' - ' . $horaire['fermeture'];
                                            } else {
                                                echo $horaire['ouverture'] . ' - ' . $horaire['pause_debut'] . ' ' . $horaire['pause_fin'] . ' - ' . $horaire['fermeture'];
                                            }
                                            echo "<br>";
                                        }
                                    }
                                    ?>
                                </div>
                            </a>
                            <a class="">
                                <div class="flex flex-row justify-between pt-3" id="compl-button">
                                    <p class="text-lg">Informations complémentaires</p>
                                    <p id="compl-arrow">&gt;</p>
                                </div>
                                <div class="flex flex-col py-3 px-2" id="compl-info">
                                    <?php
                                    switch ($categorie_offre) {
                                        case 'restauration':
                                            ?>
                                            <div class="text-sm flex flex-col md:flex-row">
                                                <p class="text-sm">Repas servis&nbsp;:&nbsp;</p>
                                                <p><?php echo $tags_type_repas; ?></p>
                                            </div>
                                            <?php
                                            if ($images) {
                                                ?>
                                                <img src="/public/images/offres/<?php echo $images['photo-resto']; ?>"
                                                    alt="Carte du restaurant" class="max-h-[400px] max-w-[350px] md:max-w-[500px]">
                                                <?php
                                            } else {
                                                ?>
                                                <p class="text-sm">Aucune carte pour le restaurant.</p>
                                                <?php
                                            } ?>
                                            <?php
                                            break;

                                        case 'activite':
                                            ?>
                                            <div class="text-sm flex flex-row">
                                                <p>Durée&nbsp:&nbsp</p>
                                                <p><?php echo $duree_act ?></p>
                                            </div>
                                            <p class="text-sm">Âge requis&nbsp;:&nbsp;<?php echo $age_requis_act ?> ans</p>
                                            <div class="text-sm">
                                                <?php foreach ($prestations as $presta) {
                                                    if ($presta['inclus'] == 1) {
                                                        $presta['inclus'] = 'inclus';
                                                    } else {
                                                        $presta['inclus'] = 'non inclus';
                                                    }
                                                    echo $presta['nom'] . ' : ' . $presta['inclus'] . '<br>';
                                                } ?>
                                            </div>

                                            <?php
                                            break;

                                        case 'parc_attraction':
                                            ?>
                                            <div class="text-sm flex flex-row">
                                                <p>Âge requis&nbsp:&nbsp</p>
                                                <p><?php echo $age_requis_pa ?></p>
                                                <p>&nbspans</p>
                                            </div>
                                            <div class="text-sm flex flex-row">
                                                <p>Nombre d'attraction&nbsp:&nbsp</p>
                                                <p><?php echo $nb_attractions ?></p>
                                            </div>
                                            <?php
                                            if ($images) {
                                                ?>
                                                <img src="/public/images/offres/<?php echo $images['plan']; ?>"
                                                    alt="Plan du parc d'attractions">
                                                <?php
                                            } else {
                                                ?>
                                                <p class="text-sm">Aucun plan</p>
                                                <?php
                                            } ?>
                                            <?php
                                            break;

                                        case 'visite':
                                            ?>
                                            <div class="text-sm flex flex-row">
                                                <p>Durée&nbsp:&nbsp</p>
                                                <p><?php echo $duree_vis ?></p>
                                            </div>
                                            <div class="text-sm flex flex-row">
                                                <p>Visite guidée :&nbsp</p>
                                                <p><?php echo $guide ?></p>
                                            </div>
                                            <?php if ($guideBool == true) { ?>
                                                <div class="text-sm">
                                                    <p>Langue(s) parlée(s) lors de la visite guidée :&nbsp <?php echo $langues ?>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            break;

                                        case 'spectacle':
                                            ?>
                                            <div class="text-sm flex flex-row">
                                                <p>Durée&nbsp:&nbsp</p>
                                                <p><?php echo $duree_spec ?></p>
                                            </div>
                                            <div class="text-sm flex flex-row">
                                                <p>Capacité :&nbsp</p>
                                                <p><?php echo $capacite ?></p>
                                                <p>&nbsppersonnes</p>
                                            </div>
                                            <?php
                                            break;

                                        default:
                                            ?>
                                            <p class="text-sm">Aucune informations complémentaires à afficher.</p>
                                            <?php
                                            break;
                                    }
                                    ?>
                                </div>
                            </a>
                            <?php
                            if ($categorie_offre != 'restauration' && $proAuth['type_orga'] != 'public') {
                                ?>
                                <a class="">
                                    <div class="flex flex-row justify-between pt-3" id="grille-button">
                                        <p class="text-lg">Grille tarifaire</p>
                                        <p id="grille-arrow">&gt;</p>
                                    </div>
                                    <div class="text-sm py-3 px-2" id="grille-info">
                                        <?php
                                        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/tarif_public_controller.php';
                                        $controllerTarifPublic = new TarifPublicController();
                                        $tarifs = $controllerTarifPublic->getTarifsByIdOffre($id_offre);
                                        foreach ($tarifs as $tarif) {
                                            ?>

                                            <?php echo $tarif['titre'] ?> :&nbsp;
                                            <?php echo $tarif['prix'] ?> € <br>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Partie avis blacklistés pour le professionnel -->
                    <?php if ($pro_can_answer && $id_type_offre == 2) {
                        $stmt = $dbh->prepare("
                            SELECT id_avis FROM sae_db.vue_offre_blacklistes
                            WHERE id_offre = :id_offre
                        ");
                        $stmt->bindParam('id_offre', $id_offre);
                        if ($stmt->execute()) {
                            $les_id_avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        ?>
                        <div class="w-full flex flex-col">
                            <div id="blacklistes-button" class="flex gap-4 items-center">
                                <h3 class="text-lg">Avis blacklisté(s) - (<?php echo $stmt->rowCount() ?>)</h3>
                                <p id="blacklistes-arrow">&gt;</p>
                            </div>

                            <div id="avis-blacklistes-container" class="flex flex-col items-center gap-1">
                                <?php foreach ($les_id_avis as $id_avis) {
                                    $id_avis = $id_avis['id_avis'];
                                    $mode = 'avis';
                                    $is_reference = false;
                                    include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
                                } ?>
                            </div>
                        </div>

                    <?php } ?>

                    <!-- Partie avis -->
                    <div class="!mt-10 flex flex-col gap-2">
                        <div id="avis-button" class="w-full flex gap-4 items-center">
                            <h3 class="text-lg">Avis</h3>
                            <p id="avis-arrow">&gt;</p>
                            <?php
                            // Moyenne des notes quand il y en a une
                            if (isset($moyenne) && 0 <= $moyenne && $moyenne <= 5) {
                                $n = $moyenne ?>
                                <div class="flex gap-1 grow justify-end">
                                    <div title="moyenne des notes" class="flex gap-1 shrink-0">
                                        <?php for ($i = 0; $i < 5; $i++) {
                                            if ($n >= 1) {
                                                ?>
                                                <img class="w-3" src="/public/icones/egg-full.svg" alt="1 point de note">
                                                <?php
                                            } else if ($n > 0) {
                                                ?>
                                                    <img class="w-3" src="/public/icones/egg-half.svg" alt="0.5 point de note">
                                                <?php
                                            } else {
                                                ?>
                                                    <img class="w-3" src="/public/icones/egg-empty.svg" alt="0 point de note">
                                                <?php
                                            }
                                            $n--;
                                        }
                                        ?>
                                    </div>
                                    <p class='text-sm flex items-center pt-1.5 px-1.5'>
                                        <?php echo number_format($moyenne, 1, ',', '') ?>
                                    </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

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
                                $mode = 'mon_avis';
                                include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
                                ?>
                            <?php } else {
                                ?>
                                <!-- FORMULAIRE DE CRÉATION D'AVIS -->
                                <div class="flex flex-col gap-2">
                                    <button onclick="document.getElementById('avis_formulaire').classList.toggle('hidden');"
                                        class="text-sm py-2 px-4 rounded-full bg-secondary  text-white self-end flex items-center gap-2">
                                        <i class="fa-solid fa-pen"></i>
                                        Rédiger un avis
                                    </button>

                                    <form id="avis_formulaire" action="/scripts/creation_avis.php" method="POST"
                                        class="hidden flex flex-col gap-4">

                                        <!-- Titre de l'avis -->
                                        <div>
                                            <label for="titre">Titre</label>
                                            <input type="text" name="titre" id="titre" placeholder="Titre de l'avis"
                                                class="w-full border border-black  p-1" required>
                                        </div>

                                        <!-- Commentaire de l'avis -->
                                        <textarea name="commentaire" id="commentaire" placeholder="Votre commentaire"
                                            class="w-full border border-black  p-1"></textarea>

                                        <!-- Note globale donnée (pour toutes les offres) -->
                                        <div>
                                            <label for="note_globale">Note globale</label>
                                            <select name="note_globale" id="note_globale" class="p-1 py-2 " required>
                                                <option value="" selected disabled>...</option>
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

                                        <?php
                                        // Notes additionnelles pour les restaurants
                                        if ($categorie_offre == 'restauration') { ?>
                                            <div>
                                                <label for="note_ambiance">Ambiance</label>
                                                <select name="note_ambiance" id="note_ambiance" class="p-1 py-2 " required>
                                                    <option value="" selected disabled>...</option>
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

                                            <div>
                                                <label for="note_service">Service</label>
                                                <select name="note_service" id="note_service" class="p-1 py-2 " required>
                                                    <option value="" selected disabled>...</option>
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

                                            <div>
                                                <label for="note_cuisine">Cuisine</label>
                                                <select name="note_cuisine" id="note_cuisine" class="p-1 py-2 " required>
                                                    <option value="" selected disabled>...</option>
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

                                            <div>
                                                <label for="note_rapport">Rapport qualité / prix</label>
                                                <select name="note_rapport" id="note_rapport" class="p-1 py-2 " required>
                                                    <option value="" selected disabled>...</option>
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

                                            <?php
                                        }
                                        ?>

                                        <!-- Date de l'expérience -->
                                        <div>
                                            <label for="date_experience">Date de l'expérience</label>
                                            <input type="date" name="date_experience" id="date_experience"
                                                value="Date de votre expérience" required>
                                        </div>

                                        <!-- Contexte de passage -->
                                        <div>
                                            <label for="contexte_passage">Contexte de passage</label>
                                            <select name="contexte_passage" id="contexte_passage" class="p-1 py-2 " required>
                                                <option value="" selected disabled>...</option>
                                                <option value="en solo">en solo</option>
                                                <option value="en couple">en couple</option>
                                                <option value="entre amis">entre amis</option>
                                                <option value="pour le travail">pour le travail</option>
                                                <option value="en famille">en famille</option>
                                            </select>
                                        </div>

                                        <!-- Champs cachés pour transmettre des donées à la création de l'offre -->
                                        <input type="text" id='id_offre' name='id_offre' hidden
                                            value="<?php echo $_SESSION['id_offre'] ?>">
                                        <input type="text" id='id_membre' name='id_membre' hidden
                                            value="<?php echo $_SESSION['id_membre'] ?>">

                                        <!-- Publier l'avis ou annuler l'écriture -->
                                        <div class="flex justify-end gap-3 items-center">
                                            <div onclick="document.getElementById('avis_formulaire').classList.toggle('hidden');"
                                                class="text-sm py-2 px-4 rounded-full text-secondary self-end flex items-center gap-2 border border-secondary">
                                                <p>Annuler</p>
                                            </div>

                                            <input type="submit" value="+ Publier"
                                                class="text-sm py-2 px-4 rounded-full bg-secondary text-white self-end">
                                        </div>

                                        <hr class="w-1/2 border border-black self-end my-2  bg-black">
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
                        } else if (!isset($_SESSION['id_pro'])) { ?>
                                <p class="text-sm italic"><a href='/connexion' class="underline">Connectez-vous</a>
                                    pour rédiger un
                                    avis</p>
                            <?php
                        }
                        ?>

                        <!-- Conteneur pour tous les avis -->
                        <div id="avis-container" class="grid grid-cols-1 gap-6 items-center w-full justify-center">
                        </div>
                    </div>

                    <!-- Bouton pour charger plus d'avis -->
                    <div class="flex gap-2 items-center justify-center self-end">
                        <button
                            class="text-sm py-2 px-4 rounded-full border border-secondary hover:bg-secondary hover:text-white"
                            id="load-more-btn">
                            Afficher plus
                        </button>
                        <!-- Symbole de chargement quand les avis chargent -->
                        <img id="loading-indicator" class="w-8 h-6" style="display: none;"
                            src="/public/images/loading.gif" alt="Chargement...">
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
                                        try {
                                            $('#avis-container').append(lesAvisCharges);
                                        } catch (e) {
                                            console.log(e.getMessage());
                                        }

                                        // Pour l'éventuel prochain chargement, incrémenter le curseur
                                        idx_avis += 3;
                                    } else {
                                        // Ne plus pouvoir cliquer sur le bouton quand il n'y a plus d'avis
                                        $('#load-more-btn').prop('disabled', true).text('');
                                        document.getElementById('load-more-btn').classList.add('hidden');
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
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>

    <script>
        // Configurer les flèches pour faire des dropdown menu stylés
        function setupToggle(arrowID, buttonID, infoID) {
            const button = document.getElementById(buttonID);
            const arrow = document.getElementById(arrowID);
            const info = document.getElementById(infoID);
            arrow.classList.toggle('rotate-90');

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
        <?php if ($pro_can_answer) { ?>
            setupToggle('blacklistes-arrow', 'blacklistes-button', 'avis-blacklistes-container');
        <?php } ?>
        setupToggle('avis-arrow', 'avis-button', 'avis-container');
        // setupToggle('grille-arrow', 'grille-button', 'grille-info');

        function sendReaction(idAvis, action) {
            const thumbDown = document.getElementById('thumb-down-' + idAvis);
            const thumbUp = document.getElementById('thumb-up-' + idAvis);
            const dislikeCountElement = document.getElementById(`dislike-count-${idAvis}`);
            const likeCountElement = document.getElementById(`like-count-${idAvis}`);

            // Réinitialisation des icônes
            thumbDown.classList.remove('fa-solid', 'text-rouge-logo');
            thumbDown.classList.add('fa-regular');

            thumbUp.classList.remove('fa-solid', 'text-secondary');
            thumbUp.classList.add('fa-regular');

            // Restauration des événements onclick par défaut
            thumbDown.onclick = function () {
                sendReaction(idAvis, 'down'); // Nouvelle action
            };

            thumbUp.onclick = function () {
                sendReaction(idAvis, 'up'); // Nouvelle action
            };

            // Gestion de la réaction "down"
            if (action === 'down' || action === 'upTOdown') {
                thumbDown.classList.remove('fa-regular');
                thumbDown.classList.add('fa-solid', 'text-rouge-logo');

                // Incrémentation du compteur de dislikes
                const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
                dislikeCountElement.textContent = currentDislikes + 1;

                // Décrémentation du compteur de likes si l'utilisateur change de réaction
                if (action === 'upTOdown') {
                    const currentLikes = parseInt(likeCountElement.textContent) || 0;
                    likeCountElement.textContent = currentLikes - 1;
                }

                // Mise à jour des événements onclick
                thumbDown.onclick = function () {
                    sendReaction(idAvis, 'downTOnull'); // Nouvelle action pour annuler
                };

                thumbUp.onclick = function () {
                    sendReaction(idAvis, 'downTOup'); // Nouvelle action
                };
            }

            // Gestion de la réaction "up"
            if (action === 'up' || action === 'downTOup') {
                thumbUp.classList.remove('fa-regular');
                thumbUp.classList.add('fa-solid', 'text-secondary');

                // Incrémentation du compteur de likes
                const currentLikes = parseInt(likeCountElement.textContent) || 0;
                likeCountElement.textContent = currentLikes + 1;

                // Décrémentation du compteur de dislikes si l'utilisateur change de réaction
                if (action === 'downTOup') {
                    const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
                    dislikeCountElement.textContent = currentDislikes - 1;
                }

                // Mise à jour des événements onclick
                thumbUp.onclick = function () {
                    sendReaction(idAvis, 'upTOnull'); // Nouvelle action pour annuler
                };

                thumbDown.onclick = function () {
                    sendReaction(idAvis, 'upTOdown'); // Nouvelle action
                };
            }

            if (action === 'upTOnull') {
                const currentLikes = parseInt(likeCountElement.textContent) || 0;
                likeCountElement.textContent = currentLikes - 1;
            }

            if (action === 'downTOnull') {
                const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
                dislikeCountElement.textContent = currentDislikes - 1;
            }

            // Envoi de la requête pour mettre à jour la réaction
            const url = `/scripts/thumb.php?id_avis=${idAvis}&action=${action}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    const resultDiv = document.getElementById(`reaction-result-${idAvis}`);
                    if (data.success) {
                        resultDiv.innerHTML = `Réaction mise à jour : ${data.message}`;
                    } else {
                        resultDiv.innerHTML = `Erreur : ${data.message}`;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la requête:', error);
                });
        }
    </script>
</body>

</html>