<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <link rel="icon" type="image" href="/public/images/favicon.png">
    
    <link rel="stylesheet" href="/styles/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="/scripts/loadComponentsPro.js" defer=""></script>
    <script type="module" src="/scripts/main.js" defer=""></script>
    
    <title>401 Accès refusé - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>
    <div id="menu-pro"></div>
    <main class="w-full mt-20 m-auto max-w-[1280px] p-2">
        <div class="text-center">
            <h1 class="font-cormorant text-[10rem]">401</h1>
            <p>Vous ne pouvez pas accéder à cette page.</p>
            <img src="https://i.pinimg.com/originals/e0/5a/70/e05a70b23f36987ff395063a1e193db7.gif" class="mt-10 m-auto rounded-lg" alt="tottereau" width="250">
        </div>
    </main>
    <div id="footer-pro" class=""></div>
</body>

</html>