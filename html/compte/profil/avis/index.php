<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Récupérer les infos du membre
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$membre = verifyMember();
$id_membre = $_SESSION['id_membre'];

// Controllers
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
$controllerMembre = new MembreController();
$membre = $controllerMembre->getInfosMembre($id_membre);

if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
    $controllerMembre->updateMembre($membre['id_compte'], false, false, false, false, $_POST['pseudo'], false);
    unset($_POST['pseudo']);
}
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

    <title>Mes avis - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <?php
    $id_membre = $_SESSION['id_membre'];

    // Récupération des informations du compte
    $stmt = $dbh->prepare('SELECT * FROM sae_db._membre WHERE id_compte = :id_membre');
    $stmt->bindParam(':id_membre', $id_membre);
    $stmt->execute();
    $id_membre = $stmt->fetch(PDO::FETCH_ASSOC)['id_compte']; ?>

    <main class="w-full flex justify-center grow">
        <div class="max-w-[1280px] w-full p-2 flex justify-center">
            <div id="menu">
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/menu.php';
                ?>
            </div>

            <div class="flex flex-col p-4 md:p-2 md:mx-10 grow">
                <p class="text-xl p-4">
                    <a href="/compte">Mon compte</a>
                    >
                    <a href="/compte/profil">Profil</a>
                    >
                    <a href="/compte/profil/avis" class="underline">Avis</a>
                </p>

                <hr class="mb-8">

                <div class="flex justify-between items-center">
                    <p class="text-2xl mb-4">Mes avis</p>

                    <a class="cursor-pointer flex items-center gap-2 hover:text-primary duration-100" id="sort-button"
                        tabindex="0">
                        <i class="text xl fa-solid fa-sort"></i>
                        <p>Trier par</p>
                    </a>
                </div>

                <div class="hidden relative" id="sort-section">
                    <div
                        class="absolute top-0 right-0 z-20 self-end bg-white border border-base200  shadow-md max-w-48 p-2 flex flex-col gap-4">
                        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'date-ascending') ? '/compte/profil/avis' : '?sort=date-ascending'; ?>"
                            class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-ascending') ? '' : ''; ?> hover:text-primary duration-100">
                            <p>Plus récent au plus ancien</p>
                        </a>
                        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'date-descending') ? '/compte/profil/avis' : '?sort=date-descending'; ?>"
                            class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-descending') ? '' : ''; ?> hover:text-primary duration-100">
                            <p>Plus ancien au plus récent</p>
                        </a>
                    </div>
                </div>

                <div class="grow flex flex-col gap-4 mt-4">
                    <!-- Afficher tous les avis du membre -->
                    <?php
                    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
                    $avisController = new AvisController();
                    $tousMesAvis = $avisController->getAvisByIdMembre($id_membre);

                    if (isset($_GET['sort']) && $_GET['sort'] === 'date-ascending') {
                        usort($tousMesAvis, function ($a, $b) {
                            return strtotime($a['date_publication']) - strtotime($b['date_publication']);
                        });
                    } else if (isset($_GET['sort']) && $_GET['sort'] === 'date-descending') {
                        usort($tousMesAvis, function ($a, $b) {
                            return strtotime($b['date_publication']) - strtotime($a['date_publication']);
                        });
                    }

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
                                $mode = 'mon_avis';
                                include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
                                ?>
                            </div>

                            <script>
                                document.querySelector('#clickable_div_<?php echo $id_avis ?>')?.addEventListener('click', function () {
                                    window.location.href = '/scripts/go_to_details.php?id_offre=<?php echo $avis['id_offre'] ?>';
                                });
                            </script><?php
                        }
                    } else {
                        ?>
                        <h1 class="text-lg ">Vous n'avez publié aucun avis.</h1>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>
</body>

</html>

<script>
    // Initialisation du toggle pour le bouton et la section
    setupToggle('sort-button', 'sort-section');
</script>