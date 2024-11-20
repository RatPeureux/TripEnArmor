<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Accueil | PACT</title>

    <link rel="stylesheet" href="/styles/output.css">
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script type="module" src="/scripts/main.js" defer></script>
</head>

<body class="flex flex-col min-h-screen">

    <div id="header" class="mb-10"></div>

    <?php
    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Obtenir l'ensembre des offres
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true");
    $stmt->execute();
    $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    var_dump($toutesLesOffres);

    // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
    if (!$toutesLesOffres) {
        echo "<p class='font-bold'>Il n'existe aucune offre...</p>";
    } else {
        $allCardsTextPhone = '';
        $allCardsTextTablette = '';
        foreach ($toutesLesOffres as $offre) {
            // Avoir une variable $pro qui contient les informations du pro actuel.
            $idPro = $offre['idpro'];
            $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE id_compte = :idPro");
            $stmt->bindParam(':idPro', $idPro);
            $stmt->execute();
            $pro = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($pro) {
                $pro_nom = $pro['nompro'];
            }

            // Obtenir les différentes variables avec les infos nécessaires via des requêtes SQL sécurisées (bindParams)
            include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';

            // Ajouter le contenu des cartes pour le téléphone
            {
                $allCardsTextPhone .= "<a href='/scripts/go_to_details.php?offre_id=$offre_id'>
                        <div class='card";
                // Afficher en exergue si la carte a une option (à la une ou en relief)
                if ($option) {
                    $allCardsTextPhone .= ' active';
                }
                $allCardsTextPhone .= " relative bg-base200 rounded-xl flex flex-col'>
                                <!-- En tête -->
                                <div
                                    class='en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg'>
                                    <h3 class='text-center font-bold'>$titre_offre</h3>
                                <div class='flex w-full justify-between px-2'>
                                    <p class='text-small'>$pro_nom</p>
                                    <p class='text-small'>$categorie_offre</p>
                                </div>
                            </div>
                            <!-- Image de fond -->
                            <img class='h-48 w-full rounded-t-lg object-cover' src='/public/images/image-test.png'
                                alt='Image promotionnelle de l'offre'>
                            <!-- Infos principales -->
                            <div class='infos flex items-center justify-around gap-2 px-2 grow'>
                                <!-- Localisation -->
                                <div class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center'>
                                    <i class='fa-solid fa-location-dot'></i>
                                    <p class='text-small'>$ville</p>
                                    <p class='text-small'>$code_postal</p>
                                </div>
                                <hr class='h-20 border-black border'>
                                <!-- Description -->
                                <div class='description py-2 flex flex-col gap-2 justify-center self-stretch'>
                                    <div class='p-1 rounded-lg bg-secondary self-center'>
                                        <p class='text-white text-center text-small font-bold'>";
                // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                if ($tags) {
                    $allCardsTextPhone .= $tags;
                } else {
                    $allCardsTextPhone .= 'Aucun tag';
                }
                $allCardsTextPhone .= "</p>
                                    </div>
                                    <p class='overflow-hidden line-clamp-2 text-small'>
                                        $resume
                                    </p>
                                </div>
                                <hr class='h-20 border-black border'>
                                <!-- Notation et Prix -->
                                <div class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                                    <p class='text-small'>$prix_a_afficher</p>
                                </div>
                            </div>
                        </div>
                    </a>";
            }

            // Afficher le contenu des cartes pour la tablette
            {
                $allCardsTextTablette .= "
                <a href='/scripts/go_to_details.php?offre_id=$offre_id'>
                            <div class='card";
                // Afficher en exergue si la carte a une option (à la une ou en relief)
                if ($option) {
                    $allCardsTextTablette .= ' active';
                }
                $allCardsTextTablette .= " relative bg-base200 rounded-lg flex'>
                                    <!-- Partie gauche -->
                                    <div class='gauche grow relative shrink-0 basis-1/2 h-[280px] overflow-hidden'>
                                        <!-- En tête -->
                                        <div
                                            class='en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg'>
                                            <h3 class='text-center font-bold'>$titre_offre</h3>
                                        <div class='flex w-full justify-between px-2'>
                                            <p class='text-small'>$pro_nom</p>
                                            <p class='text-small'>$categorie_offre</p>
                                        </div>
                                    </div>
                                    <!-- Image de fond -->
                                    <img class='rounded-l-lg w-full h-full object-cover object-center'
                                        src='/public/images/image-test.png' alt='Image promotionnelle de l'offre'>
                                </div>
                                <!-- Partie droite (infos principales) -->
                                <div class='infos flex flex-col items-center basis-1/2 p-3 gap-3 justify-between'>
                                    <!-- Description -->
                                    <div class='description py-2 flex flex-col gap-2'>
                                        <div class='p-1 rounded-lg bg-secondary self-center'>
                                            <p class='text-white text-center text-small font-bold'>";
                // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                if ($tags) {
                    $allCardsTextTablette .= $tags;
                } else {
                    $allCardsTextTablette .= 'Aucun tag';
                }
                $allCardsTextTablette .= "</p>
                                        </div>
                                        <p class='overflow-hidden line-clamp-5 text-small'>
                                            $resume
                                        </p>
                                    </div>
                                    <!-- A droite, en bas -->
                                    <div class='self-stretch flex flex-col gap-2'>
                                        <hr class='border-black w-full'>
                                        <div class='flex justify-around self-stretch'>
                                            <!-- Localisation -->
                                            <div
                                                class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center'>
                                                <i class='fa-solid fa-location-dot'></i>
                                                <p class='text-small'>$ville</p>
                                                <p class='text-small'>$code_postal</p>
                                            </div>
                                            <!-- Notation et Prix -->
                                            <div
                                                class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                                                <p class='text-small'>$prix_a_afficher</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                ";
            }
        }
    }
    ?>

    <!-- VERSION TELEPHONE -->
    <main class="phone md:hidden grow p-4 flex flex-col gap-4">
        <div id="menu" class="2"></div>

        <h1 class="text-4xl">Toutes les offres</h1>

        <!--
        ### CARD COMPONENT ! ###
        Composant dynamique (généré avec les données en php)
        Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
        -->
        <?php
        if (!$toutesLesOffres) {
            echo "<p class='font-bold'>Vous n'avez aucune offre...</p>";
        } else {
            print_r($allCardsTextPhone);
        }
        ?>
    </main>

    <!-- VERSION TABLETTE -->
    <main class="hidden md:block grow mx-10 self-center rounded-lg p-2 max-w-[1280px]">
        <div class="flex gap-3">
            <!-- PARTIE GAUCHE (menu) -->
            <div id="menu" class="2"></div>

            <!-- PARTIE DROITE (offres & leurs détails) -->
            <div class="tablette p-4 flex flex-col gap-4">

                <h1 class="text-4xl">Toutes les offres</h1>

                <!--
                ### CARD COMPONENT ! ###
                Composant dynamique (généré avec les données en php)
                Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
                -->
                <?php
                if (!$toutesLesOffres) {
                    echo "<p class='font-bold'>Vous n'avez aucune offre...</p>";
                } else {
                    print_r($allCardsTextTablette);
                }
                ?>
            </div>
        </div>
    </main>

    <div id="footer"></div>

</body>

</html>