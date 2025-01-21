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

    <title>Profil du compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header.php';
    ?>

    <main class="w-full flex justify-center grow">
        <div class="max-w-[1280px] w-full p-2 flex justify-center">
            <div id="menu">
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/menu.php';
                ?>
            </div>

            <div class="flex flex-col md:mx-10 grow">
                <p class="text-xl p-4">
                    <a href="/compte">Mon compte</a>
                    >
                    <a href="/compte/profil" class="underline">Profil</a>
                </p>

                <hr class="mb-8">

                <p class="text-2xl mb-4">Informations publiques</p>

                <form action="" class="flex flex-col mb-4" method="post">

                    <label class="text-lg" for="pseudo">Nom d'utilisateur</label>
                    <input value="<?php echo $membre['pseudo'] ?>"
                        class="border border-secondary p-2 bg-white w-full h-12 mb-3 text-sm" type="text" id="pseudo"
                        name="pseudo">

                    <input type="submit" id="save" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                    </input>
                </form>


                <hr class="mb-8">

                <div class="max-w-[23rem] mx-auto">
                    <a href="/compte/profil/avis"
                        class="cursor-pointer w-full  shadow-custom space-x-8 flex items-center px-8 py-4">
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
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer.php';
    ?>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const initialValues = {
            pseudo: document.getElementById("pseudo").value,
        };

        function activeSave() {
            const save = document.getElementById("save");
            const pseudo = document.getElementById("pseudo").value;

            if (pseudo !== initialValues.pseudo) {
                save.disabled = false;
                save.classList.remove("opacity-50");
                save.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save.disabled = true;
                save.classList.add("opacity-50");
                save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("pseudo").addEventListener("input", activeSave);
    });
</script>