<?php
session_start();

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);

// Vérifier si le pro est bien connecté
include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
verifyPro();

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
                    // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                    $mode_carte = 'pro';
                    include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre.php';
                    ?>

                    <?php
                }
            }
            ?>

            <!-- Bouton de création d'offre -->
            <a href="/pro/offre/creer" class="p-4 self-center bg-transparent text-primary py-2 px-4 rounded-lg inline-flex items-center border border-primary hover:text-white hover:bg-primary hover:border-primary m-1 
            focus:scale-[0.97] duration-100">
                + Nouvelle offre
            </a>
        </div>
    </main>

    <div id="footer-pro"></div>
</body>

</html>