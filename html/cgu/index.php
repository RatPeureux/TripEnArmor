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

    <title>Conditions générales d'utilisation (CGU) - PACT</title>
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
                <strong>Nom du site :</strong> PACT<br>
                <strong>Propriétaire :</strong> TripEnArvor<br>
                <strong>Adresse :</strong> Rue Édouard Branly, 22300 Lannion<br>
                <strong>Contact :</strong> Tél. +33 2 96 46 93 00<br>
                <strong>Hébergement :</strong> Gildas "Big Papoo" Quignou, Vents d'ouest
            </p>

            <p class="text-h2">2. Acceptation des CGU</p>
            <p>
                En accédant et en utilisant ce site, vous acceptez pleinement les présentes Conditions Générales d'Utilisation.
                Si vous n'êtes pas d'accord, veuillez cesser d'utiliser le site.
            </p>

            <p class="text-h2">3. Utilisation du Site</p>
            <p>
                Le site est accessible gratuitement. Cependant, l’accès peut être suspendu pour maintenance ou en cas de force majeure.
            </p>

            <p class="text-h2">4. Propriété Intellectuelle</p>
            <p>
                Tous les contenus présents sur ce site sont protégés par le droit de la propriété intellectuelle. Toute utilisation non autorisée est interdite.
            </p>

            <p class="text-h2">5. Données Personnelles et RGPD</p>
            <p>
                Les données personnelles des utilisateurs sont collectées et traitées conformément à notre <a href="/confidentialite_et_cookies" class="underline">Politique de Confidentialité</a>.
            </p>

            <p class="text-h2">6. Cookies</p>
            <p>
                Le site utilise des cookies obligatoires pour améliorer l’expérience utilisateur.
            </p>

            <p class="text-h2">7. Responsabilités</p>
            <p>
                <strong>Responsabilité de l’éditeur :</strong> Le site décline toute responsabilité en cas d’interruptions ou d’erreurs dans les contenus.<br>
                <strong>Responsabilité de l’utilisateur :</strong> L’utilisateur s’engage à respecter la législation et à ne pas utiliser le site de manière frauduleuse.
            </p>

            <p class="text-h2">8. Liens Hypertextes</p>
            <p>
                Le site peut contenir des liens vers des sites tiers. Nous ne sommes pas responsables du contenu de ces sites.
            </p>

            <p class="text-h2">9. Modifications des CGU</p>
            <p>
                Les présentes CGU peuvent être modifiées à tout moment. Nous encourageons les utilisateurs à les consulter régulièrement.
            </p>

            <p class="text-h2">10. Loi Applicable et Juridiction</p>
            <p>
                Les présentes CGU sont régies par le droit français. En cas de litige, les tribunaux compétents seront ceux du ressort de Lannion.
            </p>

            <p class="text-h2">11. Finalité du traitement</p>
            <p>
                Les finalités du traitement de données sur la PACT pour ses utilisateurs sont :
                <ul class="list-disc list-inside">
                    <li>Garantir la clarté des informations concernant les offres des professionnels.</li>
                    <li>Rendre les offres proposées accessibles au plus grand nombre.</li>
                    <li>Permettre aux utilisateurs de partager leurs avis et d’être reconnus pour leurs contributions.</li>
                    <li>Fournir des notifications concernant l'évolution de nos contenus et de nos offres.</li>
                </ul>
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>
    
</body>
</html>