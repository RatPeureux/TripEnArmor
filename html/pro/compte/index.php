<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$pro = verifyPro();
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
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Mon compte - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col">
    <?php
    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    ?>

    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header-pro.php';
    ?>

    <main class="grow flex flex-col max-w-[1280px] md:w-full mx-auto p-2">
        <p class="text-h3 p-4"><?php echo $pro['nom_pro'] ?></p>

        <hr class="mb-4">

        <div class="grow max-w-[23rem] mx-auto gap-12 flex flex-col items-center justify-center">
            <a href="/pro/compte/profil"
                class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-user"></i>
                <div class="w-full">
                    <p class="text-h2">Profil</p>
                    <p class="text-small">Modifier mon profil public.</p>
                    <p class="text-small">Voir mes activités récentes.</p>
                </div>
            </a>
            <a href="/pro/compte/parametres"
                class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-gear"></i>
                <div class="w-full">
                    <p class="text-h2">Paramètres</p>
                    <p class="text-small">Modifier mes informations privées.</p>
                    <p class="text-small">Supprimer mon compte.</p>
                </div>
            </a>
            <a href="/pro/compte/securite"
                class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-shield"></i>
                <div class="w-full">
                    <p class="text-h2">Sécurité</p>
                    <p class="text-small">Modifier mes informations sensibles.</p>
                    <p class="text-small">Protéger mon compte.</p>
                </div>
            </a>
            
            <?php
            if (($pro['data']['type']) ==  'prive') { 
                ?>
                <a href="/pro/compte/facture"
                class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-file-invoice"></i>
                <div class="w-full">
                    <p class="text-h2">Facture</p>
                    <p class="text-small">Faire le point sur mes factures.</p>
                </div>
            </a>
            <?php
            }
            ?>

            <a href="/scripts/logout.php" onclick="return confirmLogout()"
                class="w-full h-12 p-1 font-bold text-small text-center text-wrap text-rouge-logo bg-transparent rounded-lg flex items-center justify-center border border-rouge-logo hover:text-white hover:bg-red-600 hover:border-red-600 focus:scale-[0.97]">
                Se déconnecter
            </a>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>
</body>

</html>