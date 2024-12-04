<?php
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

// Vérifier si le pro est bien connecté
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script src="/scripts/search.js"></script>
    <script type="module" src="/scripts/main.js" defer=""></script>

    <title>Mentions - Professionnel - PACT</title>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php 
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <div class="self-center  flex justify-center w-full md:max-w-[1280px] p-2">
        <main class="grow gap-4 p-4 md:p-2 flex flex-col md:mx-10 md:rounded-lg">
            <div class="flex flex-col md:flex-row item-start md:items-center gap-4">
                <!-- Icône pour revenir à la page précédente -->
                <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer mb-1"></i>
                
                <p class="text-h1">Mentions Légales</p>
            </div>

            <p class="text-h2">1. Éditeur du Site</p>
            <p>
                <strong>Nom du site :</strong> PACT<br>
                <strong>Nom de l'éditeur :</strong> FNOC<br>
                <strong>Adresse :</strong> 21 rue Case Nègres, Place d'Armes, Le Lamentin 97232, Martinique<br>
                <strong>Contact :</strong> Tél. +33 1 23 45 67 89<br>
                <strong>Numéro SIRET :</strong> 123 456 789 00012<br>
                <strong>RCS :</strong> 123 456 789 RCS FORT-DE-FRANCE
            </p>

            <p class="text-h2">2. Hébergement</p>
            <p>
                Le site est hébergé par :<br>
                <strong>Nom de l’hébergeur :</strong> Vents d'ouest<br>
                <strong>Adresse :</strong> 2 Rue du Vent Ouest, 35400 Saint-Malo, France<br>
                <strong>Contact :</strong> gildas@bigpapooXXX.com
            </p>

            <p class="text-h2">3. Directeur de la Publication</p>
            <p>
                <strong>Nom :</strong> Léo Bléas<br>
                <strong>Contact :</strong> leobleas@gmail.com
            </p>

            <p class="text-h2">4. Propriété Intellectuelle</p>
            <p>
                Tous les contenus présents sur ce site (textes, images, vidéos, logos, etc.) sont protégés par le droit de la propriété intellectuelle.
                Toute reproduction ou utilisation non autorisée est interdite.
            </p>

            <p class="text-h2">5. Données Personnelles et RGPD</p>
            <p>
                Conformément au RGPD, les données collectées via ce site sont utilisées uniquement dans le cadre de son fonctionnement.
                Consultez notre <a href="/pro/confidentialite_et_cookies" class="underline">Politique de Confidentialité</a> pour en savoir plus.
            </p>

            <p class="text-h2">6. Cookies</p>
            <p>
                Ce site utilise des cookies obligatoires pour améliorer l'expérience utilisateur. Vous pouvez gérer vos préférences en matière de cookies via la bannière dédiée affichée lors de votre première visite.
            </p>

            <p class="text-h2">7. Loi Applicable et Juridiction</p>
            <p>
                Les présentes mentions légales sont régies par le droit français. En cas de litige, les tribunaux compétents seront ceux du ressort de Lannion.
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
    
</body>

</html>