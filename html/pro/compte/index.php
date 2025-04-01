<?php
session_start();

// Récupérer les infos du pro
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- NOS FICHIERS -->
    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js"></script>

    <title>Mon compte - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col">
    <?php
    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    ?>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <main class="grow flex flex-col md:w-full mx-auto p-6">
        <p class="text-xl p-4"><?php echo $pro['nom_pro'] ?></p>

        <hr class="mb-8">

        <div class="grow max-w-[23rem] mx-auto gap-12 flex flex-col items-center justify-center">
            <a href="/pro/compte/profil"
                class="border hover:border-secondary cursor-pointer w-full bg-base100 space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-4xl fa-solid fa-user"></i>
                <div class="w-full">
                    <p class="text-lg">Profil</p>
                    <p class="text-sm">Modifier mon profil public.</p>
                    <p class="text-sm">Voir mes activités récentes.</p>
                </div>
            </a>
            <a href="/pro/compte/parametres"
                class="border hover:border-secondary cursor-pointer w-full bg-base100 space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-4xl fa-solid fa-gear"></i>
                <div class="w-full">
                    <p class="text-lg">Paramètres</p>
                    <p class="text-sm">Modifier mes informations privées.</p>
                </div>
            </a>
            <a href="/pro/compte/securite"
                class="border hover:border-secondary cursor-pointer w-full bg-base100 space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-4xl fa-solid fa-shield"></i>
                <div class="w-full">
                    <p class="text-lg">Sécurité</p>
                    <p class="text-sm">Modifier mes informations sensibles.</p>
                    <p class="text-sm">Protéger mon compte.</p>
                    <p class="text-sm">Récupérer ma clé API : Tchatator.</p>
                </div>
            </a>

            <?php
            if (($pro['data']['type']) == 'prive') {
                ?>
                <a href="/pro/compte/facture"
                    class="border hover:border-secondary cursor-pointer w-full space-x-8 flex items-center px-8 py-4 bg-base100">
                    <i class="w-[50px] text-center text-4xl fa-solid fa-file-invoice"></i>
                    <div class="w-full">
                        <p class="text-lg">Factures</p>
                        <p class="text-sm">Faire le point sur mes paiements réels ou prévisionnels.</p>
                    </div>
                </a>
                <?php
            }
            ?>

            <a href="/scripts/logout.php" onclick="return confirmLogout()"
                class="w-full text-white text-sm border border-rouge-logo bg-rouge-logo px-4 py-2 rounded-full hover:bg-transparent hover:text-rouge-logo flex items-center justify-center">
                Se déconnecter
            </a>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>
</body>

</html>