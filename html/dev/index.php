<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">

    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/main.js" defer></script>

    <title>Accueil | PACT</title>
</head>

<body class="flex flex-col min-h-screen">
    <?php
    // $mode = "rouge";
    // $message = "Message d'erreur";
    
    // require __DIR__ . "/../../view/bouton.php";
    // unset($mode, $message, $icone);
    // $mode = "rouge-outline";
    // $message = "Écrire";
    // $icone = "fa-solid fa-pen";
    
    // require __DIR__ . "/../../view/bouton.php";
    // unset($mode, $message, $icone);
    
    // $mode = "primary";
    // $message = "Écrire";
    // $icone = "fa-solid fa-pen";
    
    // require __DIR__ . "/../../view/bouton.php";
    // unset($mode, $message, $icone);
    
    // $mode = "primary-outline";
    // $message = "Écrire";
    // $icone = "fa-solid fa-house";
    
    // require __DIR__ . "/../../view/bouton.php";
    // unset($mode, $message, $icone);
    
    // $mode = "secondary";
    
    // require __DIR__ . "/../../view/bouton.php";
    // unset($mode);
    // $mode = "secondary-outline";
    
    // Test de la vue pour un avis
    $id_avis = 1;
    $date_publication = '2024-11-26';
    $date_experience = '2024-11-26';
    $commentaire = 'C nul ! A revoir !';
    $id_avis_reponse = 2;
    $id_membre = 1;
    $titre = 'monAvis';

    require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
    ?>
</body>

</html>