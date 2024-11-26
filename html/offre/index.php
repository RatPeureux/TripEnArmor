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
    $id_membre = $_SESSION['id_membre'];

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
            <h1 class="text-h1"><?php echo $offre['titre'] ?></h1>
            <!-- Afficher les tags de l'offre -->
            <?php
            if ($tags) {
                echo ("<h3 class='text-h3'>$tags</h3>");
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
                        <p><?php echo $numero_adresse . ' ' . $odonyme . ' ' . $complement ?></p>
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
                                        <p><?php echo $numero_adresse . ' ' . $odonyme . ' ' . $complement ?>
                                        </p>
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
                        <div class="avis">
                            <?php
                            include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="footer"></div>

</body>

</html>