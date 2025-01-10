<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>

    <script src="/scripts/filtersAndSorts.js"></script>
    <script type="module" src="/scripts/main.js" defer></script>

    <title>PACT</title>

    <script src="/scripts/search.js"></script>
</head>

<body class="flex flex-col min-h-screen overflow-hidden">

    <?php
    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

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
    $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>

    <div class="w-full flex flex-col justify-center items-center">

        <header class="flex justify-between items-center z-30 w-full h-20 top-0 mx-auto max-w-[1280px]">
            <div class="flex items-center justify-between">
                <!-- Menu Burger pour les petits écrans -->
                <div class="flex items-center gap-4 md:hidden">
                    <button onclick="toggleMenu()">
                        <i class="text-3xl fa-solid fa-bars"></i>
                    </button>
                    <a href="/">
                        <img src="/public/icones/logo.svg" alt="Logo" width="40">
                    </a>
                </div>

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2">
                    <img src="/public/icones/logo.svg" alt="Logo" width="50" class="hidden md:block">
                    <!-- <h1 class="font-cormorant uppercase text-PACT hidden md:block">PACT</h1> -->
                </a>
            </div>

            <div class="flex gap-4">
                <p class="">À la Une</p>
                <p class="">Toutes les offres</p>
            </div>

            <!-- Actions Utilisateur -->
            <div class="flex items-center gap-4">
                <?php require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
                if (isConnectedAsMember()) { ?>
                    <!-- Si connecté -->
                    <a href="/compte">
                        <i class="text-3xl fa-regular fa-user"></i>
                    </a>
                    <a href="/scripts/logout.php" class="hidden md:block flex flex-col items-center"
                        onclick="return confirmLogout()">
                        <div class="border border-primary roundex-lg p-2">
                            <p class="font-bold">Se déconnecter</p>
                        </div>
                    </a>
                <?php } else { ?>
                    <!-- Si non connecté -->
                    <a href="/connexion" class="md:hidden">
                        <i class="text-3xl fa-regular fa-user"></i>
                    </a>
                    <a href="/connexion" class="hidden md:block">
                        <div class="border border-primary roundex-lg p-2">
                            <p class="text-nowrap font-bold">Se connecter</p>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </header>
    </div>



    <main class="self-center align-center text-center w-full grow roundex-lg max-w-[1280px]">
        <h1 class="font-cormorant uppercase text-[10rem] tracking-widest text-7xl hidden md:block mb-4">PACT</h1>

        <div class="flex justify-between space-x-2 items-center mb-4">
            <h1 class="text-h3 border-b-2 border-secondary">
                Toutes les offres
            </h1>
            <h1 class="text-h3 ">
                Restaurants
            </h1>
            <h1 class="text-h3 ">
                Spectacles
            </h1>
            <h1 class="text-h3 ">
                Activités
            </h1>
            <h1 class="text-h3 ">
                Visites
            </h1>
            <h1 class="text-h3 flex justify-center items-center">
                Attractions
            </h1>
        </div>

        <!-- Barre de recherche -->
        <div class="relative flex-1 max-w-full mx-auto mb-8">
            <div class="relative flex items-center">
                <input type="text" id="search-field" placeholder="Rechercher par tags..."
                    class="w-full border border-primary p-2 roundex-full h-16 pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200"
                    aria-label="Recherche" autocomplete="off">
                <div class="absolute right-4 flex items-center justify-center transform -translate-y-1/2">
                    <i class="fa-solid fa-magnifying-glass fa-lg cursor-pointer" id="search-btn"></i>
                </div>
                <!-- Bouton de suppression -->
                <button
                    class="hidden absolute right-2 min-w-max flex items-center justify-center bg-white roundex-lg px-2 py-1"
                    id="clear-tags-btn">
                    <i class="text-xl fa-solid fa-times cursor-pointer"></i>
                </button>
            </div>
            <!-- Dropdown de recherche -->
            <div class="absolute top-full left-0 right-0 bg-white border border-base200 roundex-lg shadow-md mt-2 hidden z-10"
                id="search-menu">
            </div>
        </div>

        <?php
        // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
        if (!$toutesLesOffres) { ?>
            <div class="md:min-w-full flex flex-col gap-4">
                <?php echo "<p class='mt-4 font-bold text-h2'>Il n'existe aucune offre...</p>"; ?>
            </div>
        <?php } else { ?>
            <div class="overflow-x-auto scroll-hidden md:min-w-full flex gap-4" id="no-matches">
                <?php $i = 0;
                foreach ($toutesLesOffres as $offre) {
                    if ($i > -1) {
                        // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                        $mode_carte = 'membre';
                        require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre_carroussel.php';
                        $i++;
                    }
                } ?>
            </div>
        <?php } ?>
    </main>
</body>

</html>