<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$membre = verifyMember();
$id_membre = $_SESSION['id_membre'];

// Connexion avec la bdd
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
$controllerMembre = new MembreController();
$membre = $controllerMembre->getInfosMembre($id_membre);

if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
    $controllerMembre->updateMembre($membre['id_compte'], false, false, false, false, $_POST['pseudo'], false);
    unset($_POST['pseudo']);
}

$membre = verifyMember();
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
    <script src="/scripts/search.js"></script>
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Mes avis - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header.php';
    ?>

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

            <div class="flex flex-col md:mx-10 grow">
                <p class="text-h3 p-4">
                    <a href="/compte">Mon compte</a>
                    >
                    <a href="/compte/profil">Profil</a>
                    >
                    <a href="/compte/profil/avis" class="underline">Avis</a>
                </p>

                <hr class="mb-8">

                <div class="flex justify-between items-center">
                    <p class="text-h1">Mes avis</p>

                    <a class="cursor-pointer flex items-center gap-2 hover:text-primary duration-100" id="sort-button">
                        <i class="text xl fa-solid fa-sort"></i>
                        <p>Trier par</p>
                    </a>
                </div>

                <div class="hidden relative" id="sort-section">
                    <div class="absolute top-0 right-0 z-20 self-end bg-white border border-base200 rounded-lg shadow-md max-w-48 p-2 flex flex-col gap-4">
                        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'date-ascending') ? '/compte/profil/avis' : '?sort=date-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Plus récent au plus ancien</p>
                        </a>
                        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'date-descending') ? '/compte/profil/avis' : '?sort=date-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
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
                        <h1 class="text-h2 font-bold">Vous n'avez publié aucun avis.</h1>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>
</body>

</html>

<script>
    // Fonction pour configurer un bouton qui affiche ou masque une section
    function setupToggle(buttonId, sectionId) {
        const button = document.getElementById(buttonId); // Bouton pour activer/désactiver
        const section = document.getElementById(sectionId); // Section à afficher/masquer

        if (button && section) { // Vérification que les éléments existent
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien
                section.classList.toggle('hidden'); // Alterne la visibilité de la section
            });

            // Fermer la section si l'utilisateur clique en dehors
            document.addEventListener('click', function (event) {
                if (!section.contains(event.target) && !button.contains(event.target)) {
                    section.classList.add('hidden'); // Cache la section si clic ailleurs
                }
            });
        }
    }

    // Initialisation du toggle pour le bouton et la section
    setupToggle('sort-button', 'sort-section');
</script>