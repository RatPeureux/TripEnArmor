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

    <title>Politique de confidentialité et d'utilisation des cookies - PACT</title>
</head>
<body class="min-h-screen flex flex-col justify-between">
    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header.php';
    ?>

    <div class="grow self-center flex justify-center w-full md:max-w-[1280px] p-2">
        <!-- Inclusion du menu -->
        <div id="menu">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu.php';
            ?>
        </div>

        <main class="grow gap-4 p-4 md:p-2 flex flex-col md:mx-10 md:rounded-lg">
            <p class="text-h1">Politique de Confidentialité et Cookies</p>

            <p class="text-h2">1. Introduction</p>
            <p>
                Cette politique complète nos <a href="/cgu.php" class="underline">Conditions Générales d'Utilisation</a> 
                et nos <a href="/mentions_legales.php" class="underline">Mentions Légales</a>. Elle décrit en détail
                comment nous collectons, utilisons et protégeons vos données personnelles et notre gestion des cookies.
            </p>

            <p class="text-h2">2. Responsable du Traitement</p>
            <p>
                Conformément aux Mentions Légales, le responsable du traitement est :<br>
                <strong>Nom :</strong> FNOC<br>
                <strong>Adresse :</strong> 21 rue Case Nègres, Place d'Armes, Le Lamentin, 97232, Martinique<br>
                <strong>Contact :</strong> contact@pact.com
            </p>

            <p class="text-h2">3. Données Collectées : Membre</p>
            <p>
                Nous collectons des données personnelles nécessaires au fonctionnement du site, notamment :<br>
                - <strong>Données fournies directement :</strong> Nom, Prénom, Adresse mail, Adresse postale, Numéro de téléphone,<br>
                - <strong>Données collectées automatiquement :</strong> Cookies.
            </p>

            <p class="text-h2">4. Finalités et Conservation</p>
            <p>
                Les données sont utilisées conformément à la section « Finalité du traitement » des <a href="/cgu.php" class="underline">CGU</a>.
                La durée de conservation varie selon la finalité :<br>
                - Données utilisateurs : jusqu'à la suppression du compte ou après 3 ans d'inactivité,<br>
                - Données analytiques : 13 mois pour les cookies,<br>
                Consultez la section sur vos <strong>Droits des Utilisateurs</strong> ci-dessous pour demander la suppression de vos données.
            </p>

            <p class="text-h2">5. Vos Droits</p>
            <p>
                En tant qu'utilisateur, vous disposez des droits suivants :
                <ul>
                    <li>Droit d'accès, rectification ou suppression de vos données,</li>
                    <li>Droit à la portabilité et d'opposition au traitement,</li>
                    <li>Réclamation auprès de l'autorité compétente (CNIL),</li>
                </ul>
                Pour exercer vos droits, contactez-nous : <a href="mailto:benoit.tottereau@univ-rennes.fr" class="underline">dpo@pact.com</a>.
            </p>

            <p class="text-h2">6. Cookies</p>
            <p>
                Ce site utilise des cookies pour :<br>
                - Améliorer l'expérience utilisateur,<br>
                Consultez la liste des cookies utilisés ci-dessous.
            </p>

            <p class="text-h2">7. Liste des Cookies Utilisés</p>
            <table class="table-auto border-collapse border border-base300 w-full">
                <thead>
                    <tr class="bg-base100">
                        <th class="border border-base300 p-2">Nom</th>
                        <th class="border border-base300 p-2">Type</th>
                        <th class="border border-base300 p-2">Finalité</th>
                        <th class="border border-base300 p-2">Durée</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-base300 p-2">PHPSESSID</td>
                        <td class="border border-base300 p-2">Essentiel</td>
                        <td class="border border-base300 p-2">Session active</td>
                        <td class="border border-base300 p-2">Session</td>
                    </tr>
                </tbody>
            </table>

            <p class="text-h2">8. Gestion des Cookies</p>
            <p>
                Vous pouvez gérer vos préférences en matière de cookies :
                <ul>
                    <li>- En modifiant les paramètres de votre navigateur.</li>
                </ul>
            </p>

            <p class="text-h2">9. Modifications</p>
            <p>
                Nous nous réservons le droit de modifier cette politique à tout moment. Les mises à jour seront publiées sur cette page.
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>
    
</body>
</html>