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

<body class="flex flex-col min-h-screen">

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

        <header class="flex justify-between items-center z-30 w-full h-20 top-0 mx-auto max-w-[1280px] px-4">
            <div class="flex items-center justify-between">
                <!-- Menu Burger pour les petits écrans -->
                <div class="flex items-center gap-4 md:hidden">
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

            <div class="flex gap-2">
                <a class="p-2 hover:bg-base100 rounded-lg" href="/offres/a-la-une">À la Une</a>
                <a class="p-2 hover:bg-base100 rounded-lg" href="/offres">Toutes les offres</a>
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
                        <div class="border border-primary rounded-lg p-2">
                            <p class="font-bold">Se déconnecter</p>
                        </div>
                    </a>
                <?php } else { ?>
                    <!-- Si non connecté -->
                    <a href="/connexion" class="md:hidden">
                        <i class="text-3xl fa-regular fa-user"></i>
                    </a>
                    <a href="/connexion" class="hidden md:block">
                        <div class="border border-primary rounded-lg p-2">
                            <p class="text-nowrap font-bold">Se connecter</p>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </header>
    </div>

    <main class="self-center align-center w-full grow rounded-lg max-w-[1280px] p-2">
        <h1 class="font-cormorant uppercase text-center text-[10rem] tracking-widest text-7xl hidden md:block mb-4">PACT</h1>

        <div class="searchOn text-nowrap flex flex-col sm:flex-row text-center sm:text-left gap-2 sm:gap-0 flex-wrap justify-between space-x-2 items-center mb-4">
            <h1 class="cursor-pointer text-h3 border-b-2 border-secondary hover:text-gray-600" id="all">
                Tout rechercher
            </h1>
            <h1 class="cursor-pointer text-h3 hover:text-gray-600" id="restaurants">
                Restaurants
            </h1>
            <h1 class="cursor-pointer text-h3 hover:text-gray-600" id="spectacles">
                Spectacles
            </h1>
            <h1 class="cursor-pointer text-h3 hover:text-gray-600" id="activites">
                Activités
            </h1>
            <h1 class="cursor-pointer text-h3 hover:text-gray-600" id="visites">
                Visites
            </h1>
            <h1 class="cursor-pointer text-h3 hover:text-gray-600" id="attractions">
                Attractions
            </h1>
        </div>

        <!-- Barre de recherche -->
        <div class="relative flex-1 max-w-full mx-auto mb-8">
            <div class="relative flex items-center">
                <input type="text" id="search-field" placeholder="Rechercher par tags..."
                    class="w-full border border-primary p-2 rounded-full h-16 pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200"
                    aria-label="Recherche" autocomplete="off">
                <div class="absolute right-4 flex items-center justify-center transform -translate-y-1/2">
                    <i class="fa-solid fa-magnifying-glass fa-lg cursor-pointer" id="search-btn"></i>
                </div>
                <!-- Bouton de suppression -->
                <button
                    class="hidden absolute right-2 min-w-max flex items-center justify-center bg-white rounded-lg px-2 py-1"
                    id="clear-tags-btn">
                    <i class="text-xl fa-solid fa-times cursor-pointer"></i>
                </button>
            </div>
            <!-- Dropdown de recherche -->
            <div class="absolute top-full left-0 right-0 bg-white border border-base200 rounded-lg shadow-md mt-2 hidden z-10"
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
            <div class="overflow-x-auto scroll-hidden md:min-w-full flex gap-4 mb-4 md:mb-16" id="no-matches">
                <?php $i = 0;
                foreach ($toutesLesOffres as $offre) {
                    if ($i > -1) {
                        // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                        $mode_carte = 'membre';
                        require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre_accueil.php';
                        $i++;
                    }
                } ?>
            </div>
        <?php } ?>

        <h1 class="text-h1 font-bold">Offres les plus récentes</h1>

        <?php
        // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
        if (!$toutesLesOffres) { ?>
            <div class="md:min-w-full flex flex-col gap-4">
                <?php echo "<p class='mt-4 font-bold text-h2'>Il n'existe aucune offre...</p>"; ?>
            </div>
        <?php } else { ?>
            <div class="overflow-x-auto scroll-hidden md:min-w-full flex gap-4 mb-0 md:mb-16" id="no-matches">
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

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer.php';
    ?>
</body>

</html>

<script>
    // Sélectionne tous les éléments h1 à l'intérieur du conteneur "searchOn"
    const titres = document.querySelectorAll('.searchOn h1');
    const searchField = document.getElementById('search-field');

    // Placeholder correspondant à chaque h1
    const placeholders = {
        all: "Rechercher par tags...",
        restaurants: "Rechercher des restaurants par tags...",
        spectacles: "Rechercher des spectacles par tags...",
        activites: "Rechercher des activités par tags...",
        visites: "Rechercher des visites par tags...",
        attractions: "Rechercher des parcs d'attractions par tags..."
    };

    // Fonction pour gérer le clic
    titres.forEach(titre => {
        titre.addEventListener('click', () => {
            // Retire la classe de soulignement de tous les h1
            titres.forEach(t => t.classList.remove('border-b-2', 'border-secondary'));

            // Ajoute la classe de soulignement uniquement au h1 cliqué
            titre.classList.add('border-b-2', 'border-secondary');

            // Met à jour le placeholder selon l'élément cliqué
            const id = titre.id; // Récupère l'ID du h1 cliqué
            searchField.placeholder = placeholders[id] || "Rechercher...";
        });
    });
</script>