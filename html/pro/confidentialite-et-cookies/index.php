<?php
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

// Vérifier si le pro est bien connecté
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro(); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js" ></script>

    <title>Politique de confidentialité et d'utilisation des cookies - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header-pro.php';
    ?>

    <div class="self-center  flex justify-center w-full md:max-w-[1280px] p-2">
        <main class="grow gap-4 p-4 md:p-2 flex flex-col md:mx-10 md:">
            <p class="text-3xl">Politique de Confidentialité et Cookies</p>

            <p class="text-2xl underline">Introduction</p>
            <p>
                Cette politique complète nos <a href="/cgu.php" class="underline">Conditions Générales d'Utilisation</a>
                et nos <a href="/mentions_legales.php" class="underline">Mentions Légales</a>. Elle décrit en détail
                comment nous collectons, utilisons et protégeons vos données personnelles et notre gestion des cookies.
            </p>

            <p class="text-2xl underline">Responsable du Traitement</p>
            <p>
                Conformément aux Mentions Légales, le responsable du traitement est :<br>
                <strong>Nom :</strong> FNOC<br>
                <strong>Adresse :</strong> 2 Place de l'ÉcoleMilitaire, 75007, Paris<br>
                <strong>Contact :</strong> contact@pact.com
            </p>

            <p class="text-2xl underline">Données Collectées : Professionnel</p>
                Nous collectons des données personnelles nécessaires au fonctionnement du site, notamment :
            <ul class="list-disc list-inside">
                <li><strong>Données fournies directement :</strong> Statut de l'organisation (publique ou privée), Nom,
                    Adresse mail, Type d'organisation (Organisme public), Numéro de SIRET (Organisme privée), Adresse
                    postale, Numéro de téléphone, IBAN (Facultatif : Organisme privée),</li>
                <li><strong>Données collectées automatiquement :</strong> Cookies.</li>
            </ul>

            <p class="text-2xl underline">Finalités et Conservation</p>
                Les données sont utilisées conformément à la section « Finalité du traitement » des
                <a href="/cgu.php" class="underline">CGU</a>. La durée de conservation varie selon la finalité :
            <ul class="list-disc list-inside">
                <li>Données utilisateurs : jusqu'à la suppression du compte ou après 3 ans d'inactivité.</li>
                <li>Données analytiques : 13 mois pour les cookies.</li>
            </ul>
            Consultez la section sur vos <strong>Droits des Utilisateurs</strong> ci-dessous pour demander la
            suppression de vos données.

            <p class="text-2xl underline">Vos Droits</p>
                En tant qu'utilisateur, vous disposez des droits suivants :
            <ul class="list-disc list-inside">
                <li>Droit d'accès, rectification ou suppression de vos données,</li>
                <li>Droit à la portabilité et d'opposition au traitement,</li>
                <li>Réclamation auprès de l'autorité compétente (CNIL),</li>
            </ul>
            Pour exercer vos droits, contactez-nous : <a href="mailto:benoit.tottereau@univ-rennes.fr"
                class="underline">dpo@pact.com</a>.

            <p class="text-2xl underline">Cookies</p>
                Ce site utilise des cookies pour :
            <ul class="list-disc list-inside">
                <li>Améliorer l'expérience utilisateur,</li>
            </ul>
            Vous pouvez consulter la liste des cookies utilisés ci-dessous.

            <p class="text-2xl underline">Liste des Cookies Utilisés</p>
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

            <p class="text-2xl underline">Gestion des Cookies</p>
                Vous pouvez gérer vos préférences en matière de cookies :
            <ul class="list-disc list-inside">
                <li>En modifiant les paramètres de votre navigateur.</li>
            </ul>

            <p class="text-2xl underline">Modifications</p>
            <p>
                Nous nous réservons le droit de modifier cette politique à tout moment. Les mises à jour seront publiées
                sur cette page.
            </p>
        </main>
    </div>

    <!-- Inclusion du footer -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>

</body>

</html>