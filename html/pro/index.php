<?php
session_start();

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);

// Vérifier si le pro est bien connecté
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
// verifyPro();

// Fonction utilitaires
if (!function_exists('chaineVersMot')) {
    function chaineVersMot($str): string
    {
        return str_replace('_', " d'", ucfirst($str));
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/main.js"></script>
    <script type="module" src="/scripts/loadComponentsPro.js"></script>

    <title>PACT - Accueil</title>
</head>

<body class="flex flex-col min-h-screen">

    <div id="menu-pro" class="1"></div>
    
    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <?php
    // Connexion avec la bdd
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

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
        <div class="tablette p-4 flex flex-col">

            <div class="w-full flex justify-between items-end mt-20 mb-2">
                <h1 class="text-4xl">Mes offres</h1>

                <!-- BOUTONS DE FILTRES ET DE TRIS TABLETTE -->
                <div class="hidden md:flex gap-4">
                    <a href="#" class="flex items-center gap-2 hover:text-primary duration-100" id="filter-button-tab">
                        <i class="text xl fa-solid fa-filter"></i>
                        <p>Filtrer</p>
                    </a>
                    |
                    <a href="#" class="self-end flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tab">
                        <i class="text xl fa-solid fa-sort"></i>
                        <p>Trier par</p>
                    </a>
                </div>
            </div>

            <!-- Inclusion des interfaces de filtres/tris (tablette et +) -->
            <?php
            include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtrestris_tab_pro.php';
            ?>

            <?php
            // Obtenir les informations des offres du pro
            if (!$toutesMesOffres) { ?>
                <div class="md:min-w-full flex flex-col gap-4"> 
                    <?php echo "<p class='mt-4 font-bold text-h2'>Vous n'avez aucune offre...</p>"; ?>
                </div>
            <?php } else { ?>
                <div class="md:min-w-full flex flex-col gap-4" id="no-matches"> 
                    <?php foreach ($toutesMesOffres as $offre) {
                        // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                        $mode_carte = 'pro';
                        include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre.php';
                    } ?>
                </div>
            <?php } ?>

            <!-- Bouton de création d'offre -->
            <a href="/pro/offre/creer" class="self-center bg-transparent text-primary mt-4 py-2 px-4 rounded-lg inline-flex items-center border border-primary hover:text-white hover:bg-primary hover:border-primary m-1 
            focus:scale-[0.97] duration-100">
                + Nouvelle offre
            </a>
        </div>

        <!-- Inclusion des interfaces de filtres/tris (téléphone) -->
        <?php
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtrestris_tel.php';
        ?>
    </main>

    <div id="footer-pro"></div>

    <!-- Inclusion du menu de filtres (téléphone) -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtres_menu_pro.php';
    ?>
</body>

</html>

<script>
    // Fonction pour afficher ou masquer un conteneur de filtres
    function toggleFiltres() {
        let filtres = document.querySelector('#filtres');

        if (filtres) {
            filtres.classList.toggle('active'); // Alterne la classe 'active'
        }
    }
</script>

<script src="/scripts/filtersAndSortsPro.js"></script>