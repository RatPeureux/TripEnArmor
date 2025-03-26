<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// FONCTION UTILES
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/fonctions.php';

// Récupérer les informations du pro
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();

if (isset($_POST['email']) || isset($_POST['num_tel'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $email = false;
    $num_tel = false;

    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        unset($_POST['email']);
    }
    if (!empty($_POST['num_tel'])) {
        $num_tel = $_POST['num_tel'];
        unset($_POST['num_tel']);
    }
    if ($pro['data']['type'] == 'prive') {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
        $controllerProPrive = new ProPriveController();
        $controllerProPrive->updateProPrive($pro['id_compte'], $email, false, $num_tel);
    } else {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
        $controllerProPublic = new ProPublicController();
        $controllerProPublic->updateProPublic($pro['id_compte'], $email, false, $num_tel);
    }
}

if (isset($_POST['iban'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $iban = $_POST['iban'];
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/rib_controller.php';
    $controllerRib = new RibController();
    $rib = extraireRibDepuisIban($iban);
    if ($pro['data']['id_rib'] == null) {
        $id_rib = $controllerRib->createRib($rib['code_banque'], $rib['code_guichet'], $rib['numero_compte'], $rib['cle']);
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
        $controllerProPrive = new ProPriveController();
        $controllerProPrive->updateProPrive($pro['id_compte'], false, false, false, false, false, false, $id_rib);
    } else {
        $controllerRib->updateRib($pro['data']['id_rib'], $rib['code_banque'], $rib['code_guichet'], $rib['numero_compte'], $rib['cle']);
    }
}

if (isset($_POST['siren'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
    $controllerProPrive = new ProPriveController();
    if (!empty($_POST['siren'])) {
        $siren = $_POST['siren'];
        $controllerProPrive->updateProPrive($pro['id_compte'], false, false, false, false, false, $siren);
    }
}

if (isset($_POST['type_orga'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $type_orga = $_POST['type_orga'];
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
    $controllerProPublic = new ProPublicController();
    $controllerProPublic->updateProPublic($pro['id_compte'], false, false, false, false, false, $type_orga);
}

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

    <title>Paramètres du compte - Professionnel - PACT</title>
</head>

<?php
// Controllers
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/rib_controller.php';
$controllerRib = new RibController();
if ($pro['data']['id_rib'] != null) {
    $rib = $controllerRib->getInfosRib(id: $pro['data']['id_rib']);
}
?>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="m-auto flex flex-col">
            <p class="text-xl p-4">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="/pro/compte/parametres" class="underline">Paramètres</a>
            </p>

            <hr class="mb-8">

            <p class="text-2xl mb-4">Informations privées</p>

            <form action="/pro/compte/parametres/" class="flex flex-col" method="post">

                <label class="text-lg" for="email">Adresse mail</label>
                <input value="<?php echo $pro['email'] ?>" title="L'adresse mail doit comporter un '@' et un '.'"
                    placeholder="exemple@gmail.com"
                    class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3" type="email" id="email"
                    name="email">

                <label class="text-lg" for="num_tel">Numéro de téléphone</label>
                <input value="<?php echo $pro['tel'] ?>" pattern="^0\d( \d{2}){4}"
                    class="border text-sm border-secondary p-2 bg-white max-w-36 h-12 mb-3" id="num_tel" name="num_tel"
                    title="Le numéro doit commencer par un 0 et comporter 10 chiffres" placeholder="01 23 45 67 89">

                <input type="submit" id="save1" value="Enregistrer les modifications"
                    class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                    disabled>
            </form>

            <?php
            if ($pro['data']['type'] == 'prive') { ?>
                <hr class="mb-8">
                <form action="/pro/compte/parametres/" class="flex flex-col" method="post">

                    <label class="text-lg" for="iban">IBAN</label>
                    <input value="<?php if (isset($rib) && $rib != null) {
                        echo extraireIbanDepuisRib($rib);
                    } ?>" class="border text-sm border-secondary p-2 bg-white max-w-80 h-12 mb-3 " type="text"
                        id="iban" name="iban" pattern="^(FR)\d{2}( \d{4}){5} \d{3}$"
                        placeholder="FRXX XXXX XXXX XXXX XXXX XXXX XXX" title="Format : FRXX XXXX XXXX XXXX XXXX XXXX XXX ">

                    <input type="submit" id="save2" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>

                </form>

                <hr class="mb-8">

                <form action="/pro/compte/parametres/" class="flex flex-col" method="post">

                    <label class="text-lg" for="num_siren">Numéro SIRET</label>
                    <input id="num_siren" name="num_siren" pattern="^\d{3} \d{3} \d{3} \d{5}$"
                        title="Le numéro SIRET doit être composé de 14 chiffres" placeholder="Ex: 12345678901234"
                        value="<?php echo $pro['data']['numero_siren'] ?>"
                        class="border text-sm border-secondary p-2 bg-white max-w-44 h-12 mb-3 ">

                    <input type="submit" id="save3" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                </form>

                <?php
            } else {
                ?>
                <hr class="mb-8">
                <form action="/pro/compte/parametres/" class="flex flex-col" method="post">
                    <label class="text-lg" for="type_orga">Type d'organisation</label>
                    <input value="<?php echo $pro['data']['type_orga'] ?>"
                        class="border text-sm border-secondary p-2 bg-white max-w-36 h-12 mb-3 " type="text" id="type_orga"
                        name="type_orga">

                    <input type="submit" id="save4" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>

                </form>
                <?php
            } ?>

            <hr class="hidden mb-8">
        </div>
    </main>


    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = {
                email: document.getElementById("email"),
                num_tel: document.getElementById("num_tel"),
                iban: document.getElementById("iban"),
                siren: document.getElementById("num_siren"),
                type_orga: document.getElementById("type_orga"),
            };

            const save1 = document.getElementById("save1");
            const save2 = document.getElementById("save2");
            const save3 = document.getElementById("save3");
            const save4 = document.getElementById("save4");

            triggerSaveBtnOnInputsChange([inputs.email, inputs.num_tel], save1);
            if (inputs.iban) {
                triggerSaveBtnOnInputsChange([inputs.iban], save2);
            }
            if (inputs.sire) {
                triggerSaveBtnOnInputsChange([inputs.sire], save3);
            }
            if (inputs.type_orga) {
                triggerSaveBtnOnInputsChange([inputs.type_orga], save4);
            }
        });
    </script>

</body>

</html>