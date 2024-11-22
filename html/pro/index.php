<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
verifyPro();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/output.css">
    <script type="module" src="/scripts/main.js"></script>
    <script type="module" src="/scripts/loadComponentsPro.js"></script>
    <title>PACT - Accueil</title>
</head>

<body class="flex flex-col min-h-screen">

    <div id="menu-pro" class="1"></div>
    <div id="header-pro" class="mb-20"></div>

    <?php
    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    $id_pro = $_SESSION['id_pro'];

    // Avoir une variable $pro qui contient les informations du pro actuel.
    $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE id_compte = :id_pro");
    $stmt->bindParam(':id_pro', $id_pro);
    $stmt->execute();
    $pro = $stmt->fetch(PDO::FETCH_ASSOC);
    $pro_nom = $pro['nom_pro'];

    // Obtenir l'ensembre des offres du professionnel identifié
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre JOIN sae_db._professionnel ON sae_db._offre.id_pro = sae_db._professionnel.id_compte WHERE id_compte = :id_pro");
    $stmt->bindParam(':id_pro', $id_pro);
    $stmt->execute();
    $toutesMesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <main class="mx-10 self-center grow rounded-lg p-2 max-w-[1280px]">
        <!-- TOUTES LES OFFRES (offre & détails) -->
        <div class="tablette p-4 flex flex-col gap-8">
            <h1 class="text-4xl text-center">Mes offres</h1>

            <!--
            ### CARD COMPONENT POUR LES PROS ! ###
            Composant dynamique (généré avec les données en php)
            Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
            -->
            <?php
            if (!$toutesMesOffres) {
                echo "<p clas='font-bold'>Vous n'avez aucune offre...</p>";
            } else {
                foreach ($toutesMesOffres as $offre) {
                    // Obtenir les différentes variables avec les infos nécessaires via des requêtes SQL
                    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';
                    ?>

                    <div class="card <?php if ($option)
                        echo 'active' ?> relative min-w-[1280px] bg-base300 rounded-lg flex">

                            <!-- PARTIE DE GAUCHE -->
                            <div class="gauche relative shrink-0 basis-1/2 h-[370px] overflow-hidden">
                                <!-- En tête -->
                                <div class="en-tete flex justify-center absolute top-0 w-full">
                                    <div class="bg-bgBlur/75 backdrop-blur rounded-b-lg w-3/5">
                                        <h3 class="text-center text-h2 font-bold"><?php echo $titre_offre ?></h3>
                                    <div class="flex w-full justify-between px-2">
                                        <p class="text"><?php echo $pro_nom ?></p>
                                        <p class="text"><?php echo $categorie_offre ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Image de fond -->
                            <a href="/pages/go_to_details_pro.php?id_offre=<?php echo $id_offre ?>">
                                <img class="rounded-l-lg w-full h-full object-cover object-center"
                                    src="/public/images/image-test.png" alt="Image promotionnelle de l'offre"
                                    title="consulter les détails">
                            </a>
                        </div>


                        <!-- PARTIE DE DROITE (infos principales) -->
                        <div class="infos relative flex flex-col items-center basis-1/2 self-stretch px-5 py-3 justify-between">

                            <div class="w-full">
                                <!-- A droite, en haut -->
                                <div class="flex w-full items-center justify-between">
                                    <!-- OFFRE EN LIGNE ? -->
                                    <?php
                                    if ($est_en_ligne) {
                                        ?>
                                        <a href="/scripts/toggleLigne.php?id_offre=<?php echo $id_offre ?>"
                                            onclick="return confirm('Voulez-vous vraiment mettre <?php echo $titre_offre ?> hors ligne ?');"
                                            title=" [!!!] mettre hors-ligne">
                                            <svg class="toggle-wifi-offline p-1 rounded-lg border-rouge-logo hover:border-y-2 border-solid duration-100 hover:fill-[#EA4335]"
                                                width="55" height="40" viewBox="0 0 40 32" fill="#00350D">
                                                <path
                                                    d="M3.3876 12.6812C7.7001 8.54375 13.5501 6 20.0001 6C26.4501 6 32.3001 8.54375 36.6126 12.6812C37.4126 13.4437 38.6751 13.4187 39.4376 12.625C40.2001 11.8313 40.1751 10.5625 39.3814 9.8C34.3563 4.96875 27.5251 2 20.0001 2C12.4751 2 5.64385 4.96875 0.612605 9.79375C-0.181145 10.5625 -0.206145 11.825 0.556355 12.625C1.31885 13.425 2.5876 13.45 3.38135 12.6812H3.3876ZM20.0001 16C23.5501 16 26.7876 17.3188 29.2626 19.5C30.0939 20.2313 31.3564 20.15 32.0876 19.325C32.8189 18.5 32.7376 17.2312 31.9126 16.5C28.7376 13.7 24.5626 12 20.0001 12C15.4376 12 11.2626 13.7 8.09385 16.5C7.2626 17.2312 7.1876 18.4938 7.91885 19.325C8.6501 20.1562 9.9126 20.2313 10.7439 19.5C13.2126 17.3188 16.4501 16 20.0064 16H20.0001ZM24.0001 26C24.0001 24.9391 23.5787 23.9217 22.8285 23.1716C22.0784 22.4214 21.061 22 20.0001 22C18.9392 22 17.9218 22.4214 17.1717 23.1716C16.4215 23.9217 16.0001 24.9391 16.0001 26C16.0001 27.0609 16.4215 28.0783 17.1717 28.8284C17.9218 29.5786 18.9392 30 20.0001 30C21.061 30 22.0784 29.5786 22.8285 28.8284C23.5787 28.0783 24.0001 27.0609 24.0001 26Z" />
                                                <path class="invisible" d="M31 26.751L6 2.75098" stroke-width="3" stroke="#EA4335"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="/scripts/toggleLigne.php?id_offre=<?php echo $id_offre ?>"
                                            onclick="return confirm('Voulez-vous vraiment mettre <?php echo $titre_offre ?> en ligne ?');"
                                            title="[!!!] mettre en ligne">
                                            <svg class="toggle-wifi-online p-1 rounded-lg hover:fill-[#00350D] border-secondary hover:border-y-2 border-solid duration-100"
                                                width="55" height="40" viewBox="0 0 40 32" fill="#EA4335">
                                                <path
                                                    d="M3.3876 12.6812C7.7001 8.54375 13.5501 6 20.0001 6C26.4501 6 32.3001 8.54375 36.6126 12.6812C37.4126 13.4437 38.6751 13.4187 39.4376 12.625C40.2001 11.8313 40.1751 10.5625 39.3814 9.8C34.3563 4.96875 27.5251 2 20.0001 2C12.4751 2 5.64385 4.96875 0.612605 9.79375C-0.181145 10.5625 -0.206145 11.825 0.556355 12.625C1.31885 13.425 2.5876 13.45 3.38135 12.6812H3.3876ZM20.0001 16C23.5501 16 26.7876 17.3188 29.2626 19.5C30.0939 20.2313 31.3564 20.15 32.0876 19.325C32.8189 18.5 32.7376 17.2312 31.9126 16.5C28.7376 13.7 24.5626 12 20.0001 12C15.4376 12 11.2626 13.7 8.09385 16.5C7.2626 17.2312 7.1876 18.4938 7.91885 19.325C8.6501 20.1562 9.9126 20.2313 10.7439 19.5C13.2126 17.3188 16.4501 16 20.0064 16H20.0001ZM24.0001 26C24.0001 24.9391 23.5787 23.9217 22.8285 23.1716C22.0784 22.4214 21.061 22 20.0001 22C18.9392 22 17.9218 22.4214 17.1717 23.1716C16.4215 23.9217 16.0001 24.9391 16.0001 26C16.0001 27.0609 16.4215 28.0783 17.1717 28.8284C17.9218 29.5786 18.9392 30 20.0001 30C21.061 30 22.0784 29.5786 22.8285 28.8284C23.5787 28.0783 24.0001 27.0609 24.0001 26Z" />
                                                <path class="visible" d="M31 26.751L6 2.75098" stroke-width="3" stroke="#EA4335"
                                                    stroke-linecap="round" />
                                            </svg>
                                        </a>
                                        <?php
                                    }
                                    ?>

                                    <!-- Voir l'offre & modifier -->
                                    <div class="flex gap-10 items-center">
                                        <a href="" title="modifier l'offre">
                                            <i
                                                class="fa-solid fa-gear text-secondary text-h1 hover:text-primary duration-100"></i>
                                        </a>
                                        <a href="/pages/go_to_details.php?id_offre=<?php echo $id_offre ?>"
                                            title="voir l'offre">
                                            <i
                                                class="fa-solid fa-arrow-right text-secondary text-h1 hover:text-primary duration-100"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- A droite, au milieu : description avec éventuels tags -->
                                <div class=" description py-2 flex flex-col gap-2 w-full">
                                    <div class="flex justify-center relative">
                                        <div class="p-2 rounded-lg bg-secondary self-center">
                                            <p class="text-white text-center font-bold">
                                                <?php
                                                // Afficher les tags de l'offre (ou plats si c'est un resto), sinon indiquer qu'il n'y a aucun tag
                                                if ($tags) {
                                                    echo $tags;
                                                } else {
                                                    echo 'Aucun tag';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="line-clamp-3 text-center">
                                        <?php echo $resume ?>
                                    </p>
                                </div>
                            </div>


                            <!-- A droite, en bas -->
                            <div class="self-stretch flex flex-col shrink-0 gap-2">
                                <hr class="border-black w-full">
                                <div class="flex justify-around self-stretch">
                                    <!-- Localisation -->
                                    <div title="localisation de l'offre"
                                        class="localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <p class="text-small"><?php echo $ville ?></p>
                                        <p class="text-small"><?php echo $code_postal ?></p>
                                    </div>
                                    <!-- Notation et Prix -->
                                    <div class="localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center">
                                        <p class="text-small" title="<?php echo $title_prix ?>">
                                            <?php echo $prix_a_afficher ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Infos supplémentaires pour le pro -->
                                <div class="bg-veryGris p-3 rounded-lg flex flex-col gap-1">

                                    <!-- Avis et type d'offre -->
                                    <div class="flex justify-between">
                                        <div class="flex italic justify-start gap-4">
                                            <!-- Non vus -->
                                            <a title="avis non consultés" href="" class="hover:text-primary">
                                                <i class=" fa-solid fa-exclamation text-rouge-logo"></i>
                                                (0)
                                            </a>
                                            <!-- Non répondus -->
                                            <a title="avis sans réponse" href="" class="hover:text-primary">
                                                <i class="fa-solid fa-reply-all text-rouge-logo"></i>
                                                (0)
                                            </a>
                                            <!-- Blacklistés -->
                                            <a title="avis blacklistés" href="" class="hover:text-primary">
                                                <i class="fa-regular fa-eye-slash text-rouge-logo"></i>
                                                (0)
                                            </a>
                                        </div>
                                        <p class="text-center grow" title="type de l'offre"><?php echo $type_offre ?></p>
                                    </div>

                                    <!-- Dates de mise à jour -->
                                    <div class="flex justify-between text-small">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-rotate text-xl"></i>
                                            <p class="italic">Modifiée le <?php echo $date_mise_a_jour ?></p>
                                        </div>
                                        <!-- Cacher les options tant que ce n'est pas à développer -->
                                        <!-- <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-gears text-xl"></i>
                                            <div>
                                                <p>‘A la Une’ 10/09/24-17/09/24</p>
                                                <p>‘En relief' 10/09/24-17/09/24</p>
                                            </div>
                                        </div> -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Fin affichage des cartes
                }
            }
            ?>

            <!-- Bouton de création d'offre -->
            <a href="/pro/offre/creer" class="font-bold p-4 self-center bg-transparent text-primary py-2 px-4 rounded-lg inline-flex items-center border border-primary hover:text-white hover:bg-primary hover:border-primary m-1 
            focus:scale-[0.97] duration-100">
                + Nouvelle offre
            </a>
        </div>
    </main>

    <div id="footer-pro"></div>
</body>

</html>
