<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Détails d'une offre | PACT</title>

    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="/scripts/loadCaroussel.js" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <title>Détails d'une offre | PACT</title>
</head>

<body class="flex flex-col">

    <div id="header" class="sticky top-0 z-30 md:relative"></div>

    <?php
    $id_offre = $_SESSION['id_offre'];

    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

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
        $pro_nom = $pro['nom_pro'];
    }

    // Obtenir l'ensemble des informations de l'offre
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';
    switch ($categorie_offre) {
            case 'restauration':                                                
                // appel controlller restauration
                // $restaurtion egal Ctrl->getRestaurationById($id_offre)
                // echo $restauration['id_repas']
                include dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';
                $controllerRestauration = new RestaurationController();
                $parc_attraction = $controllerRestauration->getInfosRestauration($id_offre);
            break;
            
            case 'activite':
                include dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/activite_controller.php';
                $controllerActivite = new ActiviteController();
                $activite = $controllerActivite->getInfosActivite($id_offre);
                $duree_act = $activite['duree'];
                $duree_act = substr($duree_act, 0, -3);
                $duree_act = str_replace(':', 'h', $duree_act);

                $prestation = $activite['prestations'];

                $age_requis_act = $activite['age_requis'];
            break;
            
            case 'parc_attraction':
                include dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/parc_attraction_controller.php';
                $controllerParcAttraction = new ParcAttractionController();
                $parc_attraction = $controllerParcAttraction->getInfosParcAttraction($id_offre);
                
                $age_requis_pa = $parc_attraction['age_requis'];

                $nb_attractions = $parc_attraction['nb_attractions'];


            break;
            
            case 'visite':
                include dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/visite_controller.php';
                $controllerVisite = new VisiteController();
                $visite = $controllerVisite->getInfosVisite($id_offre);

                $duree_vis = $visite['duree'];
                $duree_vis = substr($duree_vis, 0, -3);
                $duree_vis = str_replace(':', 'h', $duree_vis);

                $guideBool = $visite['avec_guide'];
                if ($guideBool==true) {
                    $guide = 'oui';
                    include dirname(path: $_SERVER['DOCUMENT_ROOT']) . '/controller/visite_langue_controller.php';
                    $controllerLangue = new VisiteLangueController();
                    $tabLangues = $controllerLangue->getLanguesByIdVisite($id_offre);
                    $langues = '';
                    foreach ($tabLangues as $langue) {
                        $langues .= $langue['nom'] . ', ';
                    }
                    $langues = rtrim($langues, ', ');
                }
                else{
                    $guide = 'non';
                }

            break;
            
            case 'spectacle':
                include dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/spectacle_controller.php';
                $controllerSpectacle = new SpectacleController();
                $spectacle = $controllerSpectacle->getInfosSpectacle($id_offre);

                $duree_spec = $spectacle['duree'];
                $duree_spec = substr($duree_spec, 0, -3);
                $duree_spec = str_replace(':', 'h', $duree_spec);

                $capacite = $spectacle['capacite'];

            break;
            
            default:
            break;
        }  
    ?>

    <!-- VERSION TELEPHONE -->
    <main class="phone md:hidden flex flex-col">

        <div id="menu"></div>

        <!-- Slider des images de présentation -->
        <div
            class="w-full h-80 overflow-hidden relative swiper default-carousel swiper-container  border border-black rounded-lg">
            <!-- Wrapper -->
            <div class="swiper-wrapper">
                <!-- Image n°1 -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test.png" alt="">
                </div>
                <!-- Image n°2... etc -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test2.jpg" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test3.jpg" alt="">
                </div>
            </div>
            <!-- Boutons de navigation sur la slider -->
            <a onclick="history.back()"
                class="border absolute top-2 left-2 z-20 p-2 bg-bgBlur/75 rounded-lg flex justify-center items-center my-6"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Reste des informations sur l'offre -->
        <div class="px-3 flex flex-col gap-5">
            <!-- Titre de l'offre -->
            <p class="text-h1 font-bold"><?php echo $offre['titre'] ?></p>
            <!-- Afficher les tags de l'offre -->
            <?php
            if (!$tags=='') {
                echo ("<h3 class='text-h3'>$tags</h3>");
            }else{
                echo ("<p class='text-h3'> Aucun tag à afficher</p>");
            }
            ?>

            <!-- Nom du professionnel -->
            <p class="text-small"><?php if ($pro_nom)
                echo $pro_nom ?></p>

                <!-- Prix + localisation -->
                <div class="localisation-et-prix flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-location-dot"></i>
                        <div class="text-small">
                            <p><?php echo $ville . ', ' . $code_postal ?></p>
                        <p><?php echo $adresse['numero'] . ' ' . $adresse['odonyme'] . ' ' . $adresse['complement'] ?></p>
                    </div>
                </div>
                <p class="prix font-bold"><?php echo $prix_a_afficher ?></p>
            </div>

            <!-- Description détaillée -->
            <div class="description flex flex-col gap-2">
                <h3>À propos</h3>
                <p class="text-justify text-small px-2">
                    <?php echo $description ?>
                </p>
            </div>
        </div>
    </main>

    <!-- VERSION TABLETTE -->
    <main class="hidden md:block mx-10 self-center rounded-lg p-2 max-w-[1280px]">
        <div class="flex gap-3">
            <!-- PARTIE GAUCHE (menu) -->
            <div id="menu"></div>

            <!-- PARTIE DROITE (offre & détails) -->
            <div class="tablette grow p-4 flex flex-col items-center gap-4">

                <!-- CAROUSSEL -->
                <div
                    class="w-full h-[500px] overflow-hidden relative swiper default-carousel swiper-container border border-black rounded-lg">
                    <!-- Wrapper -->
                    <div class="swiper-wrapper">
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test.png" alt="">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test2.jpg" alt="">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test3.jpg" alt="">
                        </div>
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
                <div class="flex flex-col gap-2">
                    <div class="flex flex-row items-center">
                        <p class="text-h1 font-bold"><?php echo $offre['titre']?></p>
                        <p class="text-h1 pt-2">&nbsp-&nbsp<?php echo $pro_nom?></p>
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
                            }else{
                                ?>
                                <div class="p-1 rounded-lg bg-secondary self-center w-full">
                                <?php
                                echo ("<p class='text-white text-center'>Aucun tag à afficher</p>");
                                ?>
                                </div>
                                <?php
                            }
                        ?>
                    <!-- Description + avis -->
                    <div class="description-et-avis flex flex-row">
                        <!-- Partie description -->
                        <div class="partie-description flex flex-col w-5/12">

                            <!-- Prix + localisation -->
                            <div class="localisation-et-prix flex flex-col gap-4">
                                <p class="text-h4 font-bold">À propos</p>
                                <div class="flex items-center gap-4 px-2">
                                    <i class="w-6 text-center fa-solid fa-location-dot"></i>
                                    <div class="text-small">
                                        <p><?php echo $ville . ', ' . $code_postal ?></p>
                                        <p><?php echo $adresse['numero'] . ' ' . $adresse['odonyme']?></p>
                                        <?php echo $adresse['complement']?>
                                    </div>
                                </div>
                                <div class="flex items-center px-2 gap-4">
                                    <i class="w-6 text-center fa-solid fa-money-bill"></i>
                                    <p class="prix text-small"><?php echo $prix_a_afficher ?></p>
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
                        <div class="avis w-7/12 px-2 py-3">
                            <!-- Horaire -->
                            <a href="" class="">
                                <div class="flex flex-row justify-between" id="horaire-button">
                                    <p class="text-h4 font-bold">Horaire</p>
                                    <p id="horaire-arrow">></p>
                                </div>
                                <div class="hidden text-small py-3" id="horaire-info">
                                        <p>Voici les supers horaires gaming</p>
                                </div>
                            </a>
                            <a href="" class="">
                                <div class="flex flex-row justify-between pt-3" id="compl-button">
                                    <p class="text-h4 font-bold">Informations complémentaires</p>
                                    <p id="compl-arrow">></p>
                                </div>
                                <div class="flex flex-col py-3 hidden" id="compl-info">
                                    <?php 
                                        switch ($categorie_offre) {
                                            case 'restauration':                                                
                                                            $tags_type_repas = 'Petit-dej, Brunch, Déjeuner, Dîner, Goûter';
                                                            ?>
                                                            <div class="text-small flex flex-row">
                                                                <p class="text-small">Repas servis&nbsp:&nbsp</p>
                                                                <p><?php echo $tags_type_repas?></p>
                                                            </div>
                                                            <?php                                
                                                break;
                                                
                                                case 'activite':
                                                    ?>
                                                    <div class="text-small flex flex-row">
                                                        <p>Durée&nbsp:&nbsp</p>
                                                        <p><?php echo $duree_act ?></p>
                                                    </div>
                                                    <div class="text-small flex flex-row">
                                                        <p>Âge requis&nbsp:&nbsp</p>
                                                        <p><?php echo $age_requis_act ?></p>
                                                        <p>&nbspans</p>
                                                    </div>
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
                                                    <div class="text-small flex flex-row">
                                                        <p>Langue(s) parlée(s) lors de la visite guidée :&nbsp</p>
                                                        <p><?php echo $langues ?></p>                                               
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
                            if($categorie_offre != 'restauration'){
                                ?>
                                <a href="" class="">
                                    <div class="flex flex-row justify-between pt-3" id="grille-button">
                                        <p class="text-h4 font-bold">Grille tarifaire</p>
                                        <p id="grille-arrow">></p>
                                    </div>
                                    <div class="hidden text-small py-3" id="grille-info">
                                            <p>Voici les prix super bien</p>
                                    </div>
                                </a>
                                <?php
                            }
                            ?>
                            <div class="pt-3">
                            <p class="text-h4 font-bold">Avis</p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>

    <div id="footer"></div>

</body>
<script>
    function setupToggle(arrowID, buttonID, infoID) {
        const button = document.getElementById(buttonID);
        const arrow = document.getElementById(arrowID);
        const info = document.getElementById(infoID);

        button.addEventListener('click', function(event) {
            event.preventDefault();
            arrow.classList.toggle('rotate-90');
            info.classList.toggle('hidden');
        });
    }

    setupToggle('horaire-arrow', 'horaire-button', 'horaire-info');
    setupToggle('compl-arrow', 'compl-button', 'compl-info');
    setupToggle('grille-arrow', 'grille-button', 'grille-info');


</script>
</html>