<?php
session_start();
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

    <title>Mes avis - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <div class="flex w-full items-center">
            <a href="" onclick="toggleMenu()" class="mr-4 md:hidden">
                <i class="text-3xl fa-solid fa-bars"></i>
            </a>
            <p class="text-h2">
                <a href="/compte">Mon compte</a>
                >
                <a href="/compte/profil">Profil</a>
                >
                <a href="/compte/profil/avis" class="underline">Avis</a>
            </p>
        </div>
    </header>

    <?php
    $id_membre = $_SESSION['id_membre'];

    // Connexion avec la bdd
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Récupération des informations du compte
    $stmt = $dbh->prepare('SELECT * FROM sae_db._membre WHERE id_compte = :id_membre');
    $stmt->bindParam(':id_membre', $id_membre);
    $stmt->execute();
    $id_membre = $stmt->fetch(PDO::FETCH_ASSOC)['id_compte']; ?>

    <main class="w-full flex justify-center grow">
        <div class="max-w-[1280px] w-full p-2 flex justify-center">
            <div id="menu">
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu.php';
                ?>
            </div>

            <div class="flex flex-col md:mx-10 gap-5 grow">
                <!-- Afficher tous les avis du membre -->
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
                $avisController = new AvisController();
                $tousMesAvis = $avisController->getAvisByIdMembre($id_membre);

                if ($tousMesAvis) {
                    foreach ($tousMesAvis as $avis) {
                        // Savoir si l'offre correspondante est en ligne pour savoir si l'on peut cliquer sur l'avis
                        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/offre.php';
                        $offre = Offre::getOffreById($avis['id_offre']);
                        $id_avis = $avis['id_avis']; ?>

                        <div id="<?php if ($offre['est_en_ligne']) {
                            echo "clickable_div_$id_avis";
                        }
                        ?>" class="shadow-lg <?php if (!$offre['est_en_ligne']) {
                            echo 'opacity-50';
                        } ?>" title="<?php if (!$offre['est_en_ligne']) {
                             echo 'offre indisponible';
                         } ?>">
                            <?php
                            include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/mon_avis_view.php';
                            ?>
                        </div>

                        <script>
                            const clickableDiv = document.querySelector('#clickable_div_<?php echo $id_avis ?>');
                            if (clickableDiv) {
                                clickableDiv.addEventListener('click', function () {
                                    window.location.href = '/scripts/go_to_details.php?id_offre=<?php echo $avis['id_offre'] ?>';
                                });
                            }
                        </script>

                        <?php
                    }
                } else {
                    ?>
                    <h1 class="mt-4 text-h2 font-bold">Vous n'avez publié aucun avis.</h1>
                    <?php
                }
                ?>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>
</body>

</html>