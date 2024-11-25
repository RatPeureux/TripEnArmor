<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Accueil | PACT</title>

    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script type="module" src="/scripts/main.js" defer></script>
</head>

<body class="flex flex-col min-h-screen">
    <?php
    $mode = "erreur";
    $className = "font-bold";
    $message = "Message d'erreur";

    require dirname($_SERVER['DOCUMENT_ROOT']) . "/../view/bouton.php";
    unset($mode, $className, $message);

    $mode = "succes";
    $className = "font-bold";
    $message = "Message de succès";

    require dirname($_SERVER['DOCUMENT_ROOT']) . "/../view/bouton.php";
    unset($mode, $className, $message);
    ?>
</body>

</html>