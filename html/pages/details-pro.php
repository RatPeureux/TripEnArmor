<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/output.css">
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="/scripts/loadcaroussel.js" type="module"></script>

    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <title>Détails d'une offre</title>
</head>
<body class="flex flex-col">

    <div id="header-pro" class="mb-10"></div>

    <?php
        session_start();
        $offre_id = $_SESSION['offre_id'];
        $idPro = $_SESSION['id_pro'];

        // Connexion avec la bdd
        include('../../php-files/connect_params.php');
        $dbh = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Avoir une variable $pro qui contient les informations du pro actuel.
        $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE id_compte = $idPro");
        $stmt->execute();
        $pro = $stmt->fetch(PDO::FETCH_ASSOC);
        $pro_nom = $pro['nompro'];

        // Obtenir l'ensemble des offres du professionnel identifié
        $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE offre_id = $offre_id");
        $stmt->execute();
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
        $description = $offre['description_offre'];
        $resume = $offre['resume_offre'];
        $est_en_ligne = $offre['est_en_ligne'];
        $prix_mini = $offre['prix_mini'];
        $date_mise_a_jour = $offre['date_mise_a_jour'];
        $titre_offre = $offre['titre'];
            // Obtenir la catégorie de l'offre
        $stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_categorie WHERE offre_id = $offre_id");
        $stmt->execute();
        $categorie_offre = $stmt->fetch(PDO::FETCH_ASSOC)['type_offre'];
            // Obtenir la date de mise à jour
        $est_en_ligne = $offre['est_en_ligne'];
        $date_mise_a_jour = $offre['date_mise_a_jour'];
        $date_mise_a_jour = new DateTime($date_mise_a_jour);
        $date_mise_a_jour = $date_mise_a_jour->format('d/m/Y');
            // Obtenir les tarifs minimaux et maximaux
        $stmt = $dbh->prepare("SELECT * FROM sae_db._tarif_public WHERE offre_id = $offre_id");
        $stmt->execute();
        $allTarifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tarif_min = 99999; $tarif_max = 0;
        foreach($allTarifs as $tarif) {
            if ($tarif['prix'] > $max_tarif_max) {
                $tarif_max = $tarif['prix'];
            }
            if ($tarif['prix'] < $tarif_min) {
                $tarif_min = $tarif['prix'];
            }
        }
            // Détails de l'adresse
        $adresse_id = $offre['adresse_id'];
        $stmt = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE adresse_id = $adresse_id");
        $stmt->execute();
        $adresse = $stmt->fetch(PDO::FETCH_ASSOC);
        $code_postal = $adresse['code_postal'];
        $ville = $adresse['ville'];
        $numero_adresse = $adresse['numero'];
        $odonyme = $adresse['odonyme'];
        $complement_adresse = $adresse['complement_adresse'];

        // AFFICHAGES SPÉCIFIQUES À CERTAINES CATÉGORIES
            // Afficher le prix comme une gamme (restauration) ou comme une fourchette de prix
        $prix_a_afficher;
        if ($categorie_offre == 'restauration') {
            $stmt = $dbh->prepare("SELECT * FROM sae_db._restauration WHERE offre_id = $offre_id");
            $stmt->execute();
            $prix_a_afficher = $stmt->fetch(PDO::FETCH_ASSOC)['gamme_prix'];
        } else {
            $prix_a_afficher = $tarif_min . '-' . $tarif_max . '€';
        }
            // Tags pour le restaurant
        if ($categorie_offre == 'restauration') {
            $stmt = $dbh->prepare("SELECT tag_restaurant_id FROM sae_db._tag_restaurant_restauration WHERE restauration_id = $offre_id");
            $stmt->execute();
            $tagIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tags = '';
            // Récup chaque nom de tag, et l'ajouter aux tags
            foreach($tagIds as $tagId) {
                $stmt = $dbh->prepare("SELECT nom_tag FROM sae_db._tag_restaurant WHERE tag_restaurant_id = $tagId");
                $stmt->execute();
                $nom_tag = $stmt->fetch(PDO::FETCH_ASSOC);
                $tags = $tags . ', ' . $nom_tag;
            }
            // Tags pour les autres types d'offre
        } else {
            $stmt = $dbh->prepare("SELECT tag_id FROM sae_db._tag_$categorie_offre WHERE id_$categorie_offre = $offre_id");
            $stmt->execute();
            $tagIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $tags = '';
            // Récup chaque nom de tag, et l'ajouter aux tags
            foreach($tagIds as $tagId) {
                $stmt = $dbh->prepare("SELECT nom_tag FROM sae_db._tag WHERE tag_id = $tagId");
                $stmt->execute();
                $nom_tag = $stmt->fetch(PDO::FETCH_ASSOC);
                $tags = $tags . ', ' . $nom_tag;
            }
        }
    ?>

    <!-- VERSION TELEPHONE -->
    <main class="phone md:hidden flex flex-col"> 

        <div id="menu"></div>

        <!-- Slider des images de présentation -->
        <div class="w-full h-80 overflow-hidden relative swiper default-carousel swiper-container  border border-black rounded-lg">
            <!-- Wrapper -->
            <div class="swiper-wrapper">
                <!-- Image n°1 -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test.jpg" alt="">
                </div>
                <!-- Image n°2... etc -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/image-test2.jpg" alt="">
                </div>
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src="/public/images/pp.png" alt="">
                </div>
            </div>
            <!-- Boutons de navigation sur la slider -->
            <a href="" onclick="history.back()" class="border absolute top-2 left-2 z-20 p-2 bg-bgBlur/75 rounded-lg flex justify-center items-center"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Reste des informations sur l'offre -->
        <div class="px-3 flex flex-col gap-5">
            <!-- Titre de l'offre -->
            <h1 class="text-h1"><?php echo $offre['titre'] ?></h1>
            <!-- Afficher les tags de l'offre -->
            <?php 
                if ($tags) {
                    echo ("<h3 class='text-h3'>$tags</h3>");
                }
            ?>

            <!-- Nom du professionnel -->
            <p class="text-small"><?php echo $pro_nom ?></p>

            <!-- Prix + localisation -->
            <div class="localisation-et-prix flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="text-small">
                        <p><?php echo $ville . ', ' . $code_postal ?></p>
                        <p><?php echo $numero_adresse . ' ' . $odonyme . ' ' . $complement_adresse?></p>
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
                <div class="w-full h-[500px] overflow-hidden relative swiper default-carousel swiper-container border border-black rounded-lg">
                    <!-- Wrapper -->
                    <div class="swiper-wrapper">
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test.jpg" alt="">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/image-test2.jpg" alt="">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full" src="/public/images/pp.png" alt="">
                        </div>
                    </div>
                    <!-- Boutons de navigation sur la slider -->
                    <div class="flex items-center gap-8 justify-center">
                        <a class="swiper-button-prev group flex justify-center items-center border border-solid rounded-full !top-1/2 -translate-y-1/2 !left-5 !bg-primary !text-white after:!text-base">
                        </a>
                        <a class="swiper-button-next group flex justify-center items-center border border-solid rounded-full !top-1/2 -translate-y-1/2 !right-5 !bg-primary !text-white after:!text-base">
                        </a>
                    </div>
                    <a href="" onclick="history.back()" class="border absolute top-2 left-2 z-20 p-2 bg-bgBlur/75 rounded-lg flex justify-center items-center"><i class="fa-solid fa-arrow-left text-h1"></i></a>
                    <div class="swiper-pagination"></div>
                </div>

                <!-- RESTE DES INFORMATIONS SUR L'OFFRE -->
                <div class="flex flex-col gap-2">
                    <h1 class="text-h1"><?php echo $offre['titre'] ?></h1>

                    <!-- Afficher les tags de l'offre -->
                    <?php 
                        if ($tags) {
                            echo ("<h3 class='text-h3'>$tags</h3>");
                        }
                    ?>
                
                    <!-- Description + avis -->
                    <div class="description-et-avis">

                        <!-- Partie description -->
                        <div class="partie-description flex flex-col gap-4">
                            <p class="professionnel"><?php echo $pro_nom ?></p>

                            <!-- Prix + localisation -->
                            <div class="localisation-et-prix flex flex-col gap-4">
                                <div class="flex items-center gap-4">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <div class="text-small">
                                        <p><?php echo $ville . ', ' . $code_postal ?></p>
                                        <p><?php echo $numero_adresse . ' ' . $odonyme . ' ' . $complement_adresse?></p>
                                    </div>
                                </div>
                                <p class="prix font-bold"><?php echo $prix_a_afficher ?></p>
                            </div>

                            <!-- Description détaillée -->
                            <div class="description flex flex-col gap-2">
                                <h3>À propos</h3>
                                <p class="text-justify text-small px-2">
                                    <?php echo $description ?>;
                                </p>
                            </div>
                        </div>

                        <!-- Partie avis -->
                        <div class="avis"></div>
                    </div>
                
                </div>
            
            </div>
        </div>
    </main>
    
    <div id="footer"></div>

</body>
</html>
