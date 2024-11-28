<?php
session_start();
// Enlever les informations gardées lors des étapes de connexion / inscription quand on reveint à la page d'accueil (seul point de sortie de la connexion / inscription)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Accueil | PACT</title>

    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script type="module" src="/scripts/main.js" defer></script>
</head>

<body class="flex flex-col min-h-screen">

    <div id="header"></div>

    <?php
    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    $sort_order = '';
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'price-ascending') {
            $sort_order = 'ORDER BY prix_mini ASC';
        } elseif ($_GET['sort'] == 'price-descending') {
            $sort_order = 'ORDER BY prix_mini DESC';
        }
    }

    // Obtenez l'ensemble des offres avec le tri approprié
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true $sort_order");
    $stmt->execute();
    $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>


    <!-- MAIN (TABLETTE et TÉLÉPHONE -->
    <div class="w-full flex justify-center p-2">
        <div class="flex gap-3 justify-center md:max-w-[1280px]">
            <div id="menu" class="2"></div>

            <main class="grow p-4 md:p-2 flex flex-col gap-4 md:mx-10 md:self-center md:rounded-lg">

                <h1 class="text-4xl">Toutes les offres</h1>

                <?php
                // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
                if (!$toutesLesOffres) {
                    echo "<p class='font-bold'>Il n'existe aucune offre...</p>";
                } else {
                    $i = 0;
                    foreach ($toutesLesOffres as $offre) {
                        if ($i < 10) {
                            // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                            $mode_carte = 'membre';
                            include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre.php';
                            $i++;
                        }
                    }
                }
                ?>
            </main>
        </div>
    </div>

    <!-- TRIER PAR -->
    <div class="hidden self-end bg-white border border-black p-2 w-44 flex flex-col" id="sorting-section-tab">
        <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note croissante</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note décroissante</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix croissant</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix décroissant</p>
        </a>
    </div>


    <div
        class="block md:hidden p-4 h-16 w-full bg-bgBlur/75 backdrop-blur border-t-2 border-black fixed bottom-0 flex items-center justify-between">
        <a href="#" class="p-2 flex items-center gap-2 hover:text-primary duration-100">
            <i class="text xl fa-solid fa-filter"></i>
            <p>Filtrer</p>
        </a>

        <a href="#" class="p-2 flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tel">
            <i class="text xl fa-solid fa-sort"></i>
            <p>Trier par</p>
        </a>
    </div>

    <div class="hidden md:hidden bg-white fixed bottom-[4.5rem] right-2 border border-black p-2 w-44 flex flex-col"
        id="sorting-section-tel">
        <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note croissante</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note décroissante</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix croissant</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>"
            class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix décroissant</p>
        </a>
    </div>

    <!-- FOOTER -->
    <div id="footer"></div>
</body>

</html>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleSortButtonTel = document.getElementById('sort-button-tel');
        const sortingOptionsTel = document.getElementById('sorting-section-tel');

        // Vérifie si les éléments existent
        if (toggleSortButtonTel && sortingOptionsTel) {
            toggleSortButtonTel.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien
                sortingOptionsTel.classList.toggle('hidden'); // Bascule la visibilité
            });
        }

        const toggleSortButtonTab = document.getElementById('sort-button-tab');
        const sortingOptionsTab = document.getElementById('sorting-section-tab');

        // Vérifie si les éléments existent
        if (toggleSortButtonTab && sortingOptionsTab) {
            toggleSortButtonTab.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien
                sortingOptionsTab.classList.toggle('hidden'); // Bascule la visibilité
            });
        }
    });
</script>