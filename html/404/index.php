<?php
// Définir le code HTTP 404
http_response_code(404);

session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';

// Enlever les informations gardées lors des étapes de connexion / inscription quand on reveint à la page d'accueil (seul point de sortie de la connexion / inscription)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);
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

    <title>404 Page non trouvée - PACT</title>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header.php';
    ?>

    <main class="grow md:w-full m-auto flex max-w-[1280px] p-2">
        <!-- Inclusion du menu -->
        <div id="menu">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/menu.php';
            ?>
        </div>

        <div class="m-auto text-center">
            <h1 class="font-cormorant text-[10rem]">404</h1>
            <p>Ce n'est pas la page que vous recherchez.</p>
            <img src="/public/images/404.gif" class="mt-10 mb-28 roundex-lg m-auto" alt="tottereau" width="250">
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer.php';
    ?>
</body>

</html>