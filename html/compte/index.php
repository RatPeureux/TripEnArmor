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
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Mon compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col">
    <?php
    $id_membre = $_SESSION['id_membre'];

    // Connexion avec la bdd
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Récupération des informations du compte
    $stmt = $dbh->prepare('SELECT * FROM sae_db._membre WHERE id_compte = :id_membre');
    $stmt->bindParam(':id_membre', $id_membre);
    $stmt->execute();
    $id_membre = $stmt->fetch(PDO::FETCH_ASSOC)['id_compte'];

    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
    $controllerMembre = new MembreController();
    $membre = $controllerMembre->getInfosMembre($id_membre);
    ?>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header.php';
    ?>

    <main class="grow max-w-[1280px] md:w-full mx-auto p-2 flex">
        <div id="menu">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/menu.php';
            ?>
        </div>

        <div class="grow flex flex-col md:mx-10">
            <p class="text-xl p-4"><?php echo $membre['prenom'] . ' ' . $membre['nom'] ?></p>

            <hr class="mb-4">

            <div class="grow flex justify-center max-w-[23rem] mx-auto gap-12 flex flex-col items-center">
                <a href="/compte/profil"
                    class="cursor-pointer w-full  shadow-custom space-x-8 flex items-center px-8 py-4">
                    <i class="w-[50px] text-center text-4xl fa-solid fa-user"></i>
                    <div class="w-full">
                        <p class="text-lg">Profil</p>
                        <p class="text-sm">Modifier mon profil public.</p>
                        <p class="text-sm">Voir mes activités récentes.</p>
                    </div>
                </a>
                <a href="/compte/parametres"
                    class="cursor-pointer w-full  shadow-custom space-x-8 flex items-center px-8 py-4">
                    <i class="w-[50px] text-center text-4xl fa-solid fa-gear"></i>
                    <div class="w-full">
                        <p class="text-lg">Paramètres</p>
                        <p class="text-sm">Modifier mes informations privées.</p>
                        <p class="text-sm">Supprimer mon compte.</p>
                    </div>
                </a>
                <a href="/compte/securite"
                    class="cursor-pointer w-full  shadow-custom space-x-8 flex items-center px-8 py-4">
                    <i class="w-[50px] text-center text-4xl fa-solid fa-shield"></i>
                    <div class="w-full">
                        <p class="text-lg">Sécurité</p>
                        <p class="text-sm">Modifier mes informations sensibles.</p>
                        <p class="text-sm">Protéger mon compte.</p>
                    </div>
                </a>

                <a href="/scripts/logout.php" onclick="return confirmLogout()"
                    class="w-full text-white text-sm border border-rouge-logo bg-rouge-logo px-4 py-2 rounded-full hover:bg-rouge-logo/90 flex items-center justify-center">
                    Se déconnecter
                </a>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer.php';
    ?>
</body>

</html>