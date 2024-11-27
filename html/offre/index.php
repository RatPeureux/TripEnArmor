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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="/scripts/loadCaroussel.js" type="module"></script>

    <!-- Pour les requêtes AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Détails d'une offre | PACT</title>
</head>

<body class="flex flex-col">

    <div id="header" class="sticky top-0 z-30 md:relative"></div>

    <?php
    $id_offre = $_SESSION['id_offre'];
    if (isset($_SESSION['id_membre'])) {
        $id_membre = $_SESSION['id_membre'];
    }

    // Connexion avec la bdd
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

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
                    <img class="object-cover w-full h-full" src='/public/images/<?php echo $categorie_offre ?>.jpg'
                        alt="image de slider">
                </div>
                <!-- Image n°2... etc -->
                <div class="swiper-slide">
                    <img class="object-cover w-full h-full" src='/public/images/<?php echo $categorie_offre ?>.jpg'
                        alt="image de slider">
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
            <p class="text-small"><?php if ($nom_pro)
                echo $nom_pro ?></p>

                <!-- Prix + localisation -->
                <div class="localisation-et-prix flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-location-dot"></i>
                        <div class="text-small">
                            <p><?php echo $ville . ', ' . $code_postal ?></p>
                        <p><?php echo $adresse['numero'] . ' ' . $adresse['odonyme'] . ' ' . $adresse['complement'] ?>
                        </p>
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
                            <img class="object-cover w-full h-full"
                                src='/public/images/<?php echo $categorie_offre ?>.jpg' alt="image de slider">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full"
                                src='/public/images/<?php echo $categorie_offre ?>.jpg' alt="image de slider">
                        </div>
                        <div class="swiper-slide !w-full">
                            <img class="object-cover w-full h-full"
                                src='/public/images/<?php echo $categorie_offre ?>.jpg' alt="image de slider">
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
                        <h1 class="text-h1 font-bold"><?php echo $offre['titre'] ?></h1>
                        <p class="professionnel text-h1">&nbsp;- <?php echo $nom_pro ?></p>
                    </div>
                    <!-- Afficher les tags de l'offre -->
                    <p>
                        <?php echo $resume ?>
                    </p>

                    <?php
                    if ($tags) {
                        echo ("<h3 class='text-h3'>$tags</h3>");
                    }
                    ?>
                    <!-- Description + avis -->
                    <div class="flex flex-row">

                        <!-- Partie description -->
                        <div class="partie-description flex flex-col w-5/12 basis-1/2">
                            <!-- Prix + localisation -->
                            <div class="localisation-et-prix flex flex-col gap-4">
                                <h3 class="font-bold">À propos</h3>
                                <div class="flex items-center gap-4 px-2">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <div class="text-small">
                                        <p><?php echo $ville . ', ' . $code_postal ?></p>
                                        <p><?php echo $adresse['numero'] . ' ' . $adresse['odonyme'] . ' ' . $adresse['complement'] ?>
                                        </p>
                                    </div>
                                </div>
                                <p class="prix px-2"><?php echo $prix_a_afficher ?></p>
                            </div>

                            <!-- Description détaillée -->
                            <div class="description flex flex-col gap-2">
                                <p class="text-justify text-small px-2">
                                    <?php echo $description ?>
                                </p>
                            </div>
                        </div>

                        <!-- Partie avis -->
                        <div class="basis-1/2 flex flex-col gap-3">
                            <h3 class="font-bold">Avis</h3>
                            <!--  verifier si le membre est bon  -->
                            <?php
                            if (isset($_SESSION['id_membre'])) {
                                ?>
                                <div class="flex flex-col gap-2">
                                    <button class="bg-primary   text-white rounded-lg p-2">Rédiger un avis</button>

                                    <form id="avis" action=" /scripts/creation_avis.php " method="post"
                                        class="flex flex-col gap-2">
                                        <input type="text" name="titre" placeholder="Titre de l'avis" class="input"
                                            required>
                                        <textarea name="description" placeholder="Description de l'avis" class="input"
                                            required></textarea>
                                        <select name="note" id="note" class="input" required>

                                            <option value="note1">1</option>
                                            <option value="note2">2</option>
                                            <option value="note3">3</option>
                                            <option value="note4">4</option>
                                            <option value="note5">5</option>

                                            <p>/5</p>

                                        </select>

                                        <input type="date" name="date_experience" id="date_experience" class="input"
                                            required>



                                        <input type="submit" value="Envoyer" class="bg-primary text-white rounded-lg p-2">
                                    </form>
                                </div>
                                <?php
                            } else { ?>
                                <p>Connectez-vous pour rédiger un avis</p>
                                <?php
                            }
                            ?>

                            <script>

                                // js pour faire apparaitre le formulaire d'avis

                                document.getElementById('avis').style.display = 'none';

                                document.querySelector('button').addEventListener('click', () => {
                                    document.getElementById('avis').style.display = 'flex';
                                });

                            </script>


                            <!-- faire un bouton pour rédiger un avis  -->

                            <!-- faire un formulaire pour pouvoir remplir les données d'un avis -->

                            <!-- le titre -->

                            <!-- la description -->

                            <!-- la note -->
                            <p></p>
                            <!-- Conteneur pour tous les avis -->
                            <div id="avis-container flex flex-col gap-2 items-center"></div>

                            <!-- Bouton pour charger plus d'avis -->
                            <button class="text-small text-end font-bold" id="load-more-btn">Afficher plus...</button>

                        </div>

                        <!-- A garder ici car il y a du PHP -->
                        <script>
                            $(document).ready(function () {
                                // Paramètres à passer au fichier PHP de chargement des avis
                                let idx_avis = 0;
                                const id_offre = <?php echo $_SESSION['id_offre'] ?>;

                                // Charger les X premiers avis
                                loadAvis();

                                // Ajouter des avis quand le bouton est cliqué
                                $('#load-more-btn').click(function () {
                                    loadAvis();
                                });

                                // Fonction pour charger X avis (en PHP), puis les ajouter à la page via AJAX JS
                                function loadAvis() {

                                    $.ajax({
                                        url: '/scripts/load_avis.php',
                                        type: 'GET',
                                        data: {
                                            id_offre: id_offre,
                                            idx_avis: idx_avis
                                        },
                                        success: function (response) {
                                            console.log(response);

                                            const lesAvis = JSON.parse(response);
                                            if (lesAvis) {
                                                // Ajouter le contenu HTML généré par loaded avis.
                                                console.log(lesAvis);
                                                // $('#avis-container').append(current_avis);
                                                // Pour l'éventuel prochain chargement, incrémenter le curseur
                                                idx_avis += 3;
                                            } else {
                                                // Ne plus pouvoir cliquer sur le bouton quand il n'y a plus d'avis
                                                $('#load-more-btn').prop('disabled', true).text('Pas d\'autre avis');
                                            }
                                        }
                                    });
                                }
                            });
                        </script>


                    </div>

                </div>

            </div>
        </div>
    </main>

    <div id="footer"></div>

</body>

</html>