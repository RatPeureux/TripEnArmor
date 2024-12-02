<?php
session_start();
// Enlever les informations gardées lors des étapes de connexion / inscription quand on reveint à la page d'accueil (seul point de sortie de la connexion / inscription)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

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
    <script src="/scripts/search.js"></script>
    <script type="module" src="/scripts/main.js" defer=""></script>

    <title>CGU - PACT</title>
</head>
<body class="min-h-screen flex flex-col justify-between">
    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header.php';
    ?>

    <div class="self-center flex justify-center w-full md:max-w-[1280px] p-2">
        <!-- Inclusion du menu -->
        <div id="menu">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu.php';
            ?>
        </div>

        <main class="grow gap-4 p-4 md:p-2 flex flex-col md:mx-10 md:rounded-lg">
            <p class="text-h1">Conditions Générales d'Utilisation (CGU)</p>

            <p class="text-h2">1. Présentation du Site</p>
            <p>
                <strong>Nom du site :</strong> PACT.<br>
                <strong>Propriétaire :</strong> TripEnArvor.<br>
                <strong>Adresse :</strong> Rue Édouard Branly, 22300 Lannion.<br>
                <strong>Contact :</strong> Tél. +33 2 96 46 93 00.<br>
                <strong>Hébergement :</strong> Gildas "Big Papoo" Quignou, Vents d'ouest.
            </p>

            <p class="text-h2">2. Acceptation des CGU</p>
            <p>
                En accédant et en utilisant ce site, vous acceptez pleinement les présentes CGU. Si vous n'acceptez pas ces conditions, veuillez cesser d'utiliser le site.
            </p>

            <p class="text-h2">3. Accès au Site</p>
            <p>
                Le site est accessible gratuitement à tout utilisateur disposant d’un accès à Internet. Cependant, des interruptions peuvent survenir pour des raisons techniques ou de maintenance. 
            </p>

            <p class="text-h2">4. Responsabilité de l'Utilisateur</p>
            <p>
                L'utilisateur s'engage à utiliser le site conformément à la loi française et à ne pas y effectuer d'activités illégales ou frauduleuses. Il est également responsable des informations qu'il transmet.
            </p>

            <p class="text-h2">5. Propriété Intellectuelle</p>
            <p>
                Tous les contenus du site (textes, images, vidéos, logos) sont protégés par le droit de la propriété intellectuelle. Toute reproduction ou utilisation non autorisée est strictement interdite.
            </p>

            <p class="text-h2">6. Données Personnelles et RGPD</p>
            <p>
                Conformément au RGPD et à la loi Informatique et Libertés, les données collectées sont traitées avec soin. Les utilisateurs disposent des droits suivants :
            </p>
            <p>
                - Droit d'accès, de rectification et de suppression.<br>
                - Droit d'opposition ou de limitation du traitement.<br>
                - Droit à la portabilité de leurs données.<br>
            </p>
            <p>
                Pour exercer ces droits, veuillez contacter notre support ou envoyer un courrier directement à TripEnArvor.
            </p>

            <p class="text-h2">7. Cookies</p>
            <p>
                Le site utilise des cookies obligatoires pour améliorer l’expérience utilisateur.
            </p>

            <p class="text-h2">8. Responsabilité et Limitation</p>
            <p>
                Le propriétaire du site ne saurait être tenu responsable des dommages directs ou indirects liés à l'utilisation du site ou des informations qui y sont contenues.
            </p>

            <p class="text-h2">9. Liens Hypertextes</p>
            <p>
                Le site peut contenir des liens vers des sites externes. Nous ne sommes pas responsables de leur contenu ou de leur utilisation.
            </p>

            <p class="text-h2">10. Modifications des CGU</p>
            <p>
                Ces CGU peuvent être modifiées à tout moment. Nous vous encourageons à les consulter régulièrement.
            </p>

            <p class="text-h2">11. Loi Applicable et Juridiction</p>
            <p>
                Les présentes CGU sont régies par le droit français. En cas de litige, les tribunaux compétents seront ceux du ressort de Lannion.
            </p>

            <p class="text-h2">12. Mentions Légales</p>
            <p>
                Voir les <a href="/legal" class="underline">mentions légales</a> du site.
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>
    
</body>
</html>