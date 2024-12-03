<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
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

    <title>Mes avis - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col">

    <div id="menu-pro">
        <?php require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php'; ?>
    </div>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <main class="flex flex-col justify-center grow md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <p class="text-h3 p-4">
            <a href="/pro/compte">Mon compte</a>
            >
            <a href="/pro/compte/profil">Profil</a>
            >
            <a href="/pro/compte/profil/avis" class="underline">Avis</a>
        </p>

        <hr class="mb-8">

        <p class="text-h1 mb-4">Mes avis</p>

        <div class="grow flex flex-col gap-4">
            <?php
            // Afficher tous les avis du professionnel
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
            $avisController = new AvisController();
            $tousMesAvis = $avisController->getAvisByIdPro($pro['id_compte']);

            if ($tousMesAvis) {
                foreach ($tousMesAvis as $avis) {
                    $id_avis = $avis['id_avis'];
                    $id_membre = $avis['id_membre'];
                    ?>

                    <div id="clickable_div_<?php echo $id_avis ?>" class="shadow-lg hover:cursor-pointer">
                        <?php
                        include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
                        ?>
                    </div>

                    <script>
                        document.querySelector('#clickable_div_<?php echo $id_avis ?>').addEventListener('click', function () {
                            window.location.href = '/scripts/go_to_details.php?id_offre=<?php echo $avis['id_offre'] ?>';
                        });
                    </script>

                    <?php
                }
            } else {
                ?>
                <h1 class="text-h2 font-bold">Aucun avis n'a été publié sur vos offres.</h1>
                <?php
            }
            ?>
        </div>

    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
</body>

</html>