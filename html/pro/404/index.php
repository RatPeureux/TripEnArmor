<?php
// Définir le code HTTP 404
http_response_code(404);

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

    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js" ></script>

    <title>404 Page non trouvée - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header-pro.php';
    ?>

    <main class="grow flex justify-center w-full  m-auto max-w-[1280px] p-2">
        <div class="flex flex-col items-center justify-center">
            <h1 class="font-cormorant text-[10rem]">404</h1>
            <p>Ce n'est pas la page que vous recherchez.</p>
            <img src="/public/images/404.gif" class="mt-10 mb-28 m-auto " alt="tottereau" width="250">
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>
</body>

</html>