<?php
session_start();

// Connexion avec la bdd
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Obtenir les informations sur le membre
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$membre = verifyMember();
$id_membre = $membre['id_compte'];

// Controllers
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
$controllerMembre = new MembreController();
$membre = $controllerMembre->getInfosMembre($id_membre);

if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $controllerMembre->updateMembre($membre['id_compte'], false, false, false, false, $_POST['pseudo'], false);
    unset($_POST['pseudo']);
}

// Rafraîchir les infos du membre avec celles mises à jours juste avant
$membre = verifyMember();
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

    <title>Profil du compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

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
                    <a href="/compte/profil" class="underline">Profil</a>
                </p>

                <hr class="mb-8">

                <p class="text-2xl mb-4">Informations publiques</p>

                <form action="/compte/profil/" class="flex flex-col mb-4" method="post">

                    <label class="text-lg" for="pseudo">Nom d'utilisateur</label>
                    <input value="<?php echo $membre['pseudo'] ?>"
                        class="border border-secondary p-2 bg-white w-full h-12 mb-3 text-sm" type="text" id="pseudo"
                        name="pseudo">

                    <input type="submit" id="save" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm mb-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                    
                </form>

                <hr class="mb-8">

                <div class="max-w-[23rem] mx-auto">
                    <a href="/compte/profil/avis"
                        class="cursor-pointer w-full bg-base100 space-x-8 flex items-center px-8 py-4">
                        <i class="w-[50px] text-center text-4xl fa-solid fa-egg"></i>
                        <div class="w-full">
                            <p class="text-lg">Avis</p>
                            <p class="text-sm">Consulter l’ensemble des avis que j’ai postés sur les différentes
                                offres
                                de la PACT.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>

    <script>
        // Lier l'input du pseudo au bouton 'enregistrer les modifications'
        window.onload = () => {
            const pseudo = document.getElementById('pseudo');
            const saveBtn = document.getElementById('save');
            triggerSaveBtnOnInputsChange([pseudo], saveBtn);
        }
    </script>
</body>

</html>
