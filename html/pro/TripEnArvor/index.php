<?php
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

// Vérifier si le pro est bien connecté
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
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
    
    <script type="module" src="/scripts/main.js" defer=""></script>

    <title>TripEnArvor et PACT</title>
</head>
<body class="min-h-screen flex flex-col">

    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php 
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <div class="grow self-center mt-20 flex justify-center w-full md:max-w-[1280px] p-2">
        <main class="grow gap-4 p-4 md:p-2 flex flex-col md:mx-10 md:rounded-lg">
            <p class="text-h1">L’association TripEnArvor et son projet PACT</p>

            <div class="block md:hidden w-full flex items-center justify-center">
                <img src="/public/images/TripEnArvor.png" alt="Logo de TripEnArvor" width="100">
            </div>
            <div class="flex gap-4">
                <div class="hidden md:block w-40 h-full flex items-center justify-center">
                    <img src="/public/images/TripEnArvor.png" alt="Logo de TripEnArvor" width="150">
                </div>
                <div class="flex flex-col gap-4">
                    <p class="text-h2">Présentation de l’association</p>
                    <p>
                        Association à but non lucratif Loi 1901, TripEnArvor a pour objectif de promouvoir le territoire Costarmoricain : activités, parcs d’attractions, visites, spectacles et restaurants.
                    </p>
                </div>
            </div>
            <p>
                Elle bénéficie ainsi d’un financement de la Région Bretagne et du Conseil Général des Côtes d’Armor, et répond à des problématiques de valorisation du patrimoine culturel et social du département.
            </p>

            <p class="text-h2">Le projet PACT</p>
            <p>
                L’enjeu majeur de l’association TripEnArvor pour 2025 était le développement et la mise en service de sa Plateforme d’Avis et Conseils Touristiques (PACT), devant contribuer au renforcement du lien entre les professionnels du tourisme (établissements privés, associations, secteur public) et la population (locale et touristique).
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
    
</body>

</html>