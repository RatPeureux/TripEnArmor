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
    $mode = "rouge";
    $message = "Message d'erreur";
    
    require __DIR__ . "/../../view/bouton.php";
    unset($mode, $message, $icone);

    $mode = "rouge-outline";
    $message = "Écrire";
    $icone = "fa-solid fa-pen";
    
    require __DIR__ . "/../../view/bouton.php";
    unset($mode, $message, $icone);
    
    $mode = "primary";
    $message = "Écrire";
    $icone = "fa-solid fa-pen";
    
    require __DIR__ . "/../../view/bouton.php";
    unset($mode, $message, $icone);

    $mode = "primary-outline";
    $message = "Écrire";
    $icone ="fa-solid fa-house";
    
    require __DIR__ . "/../../view/bouton.php";
    unset($mode, $message, $icone);


    $mode = "secondary";
    
    require __DIR__ . "/../../view/bouton.php";
    ?>
    <input type="submit" id="submit1000" class="<?php echo $modeSelected?>" value="test">
    <?php
    unset($mode);
    $mode = "secondary-outline";
    
    require __DIR__ . "/../../view/bouton.php";
    ?>

    
    <input type="submit" id="submit1000" class="<?php echo $modeSelected?>" value="test">
    <?php
    unset($mode);
    ?>
</body>

</html>