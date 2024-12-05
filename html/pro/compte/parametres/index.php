<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$pro = verifyPro();
function extraireIbanDepuisRib($rib)
{
    $res = 'FR76' . $rib['code_banque'] . $rib['code_guichet'] . $rib['numero_compte'] . $rib['cle'];

    return implode(' ', str_split($res, 4));
}

function extraireRibDepuisIban($iban)
{
    // Supprimer les espaces
    $iban = str_replace(' ', '', $iban);

    $code_banque = substr($iban, 4, 5);
    $code_guichet = substr($iban, 9, 5);
    $numero_compte = substr($iban, 14, 11);
    $cle = substr($iban, 25, 2);

    return [
        'code_banque' => $code_banque,
        'code_guichet' => $code_guichet,
        'numero_compte' => $numero_compte,
        'cle' => $cle,
    ];
}

if (isset($_POST['email']) || isset($_POST['num_tel'])) {
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
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
    $controllerProPrive = new ProPriveController();
    if (!empty($_POST['siren'])) {
        $siren = $_POST['siren'];
        $controllerProPrive->updateProPrive($pro['id_compte'], false, false, false, false, false, $siren);
    }
}

if (isset($_POST['type_orga'])) {
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

    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>

    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Paramètres du compte - Professionnel - PACT</title>
</head>

<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/rib_controller.php';
$controllerRib = new RibController();
if ($pro['data']['id_rib'] != null) {
    $rib = $controllerRib->getInfosRib(id: $pro['data']['id_rib']);
}
?>

<body class="min-h-screen flex flex-col justify-between">

    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="m-auto flex flex-col">
            <p class="text-h3 p-4">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="/pro/compte/parametres" class="underline">Paramètres</a>
            </p>

            <hr class="mb-8">

            <p class="text-h1 mb-4">Informations privées</p>

            <form action="" class="flex flex-col" method="post">

                <label class="text-h3" for="email">Adresse mail</label>
                <input value="<?php echo $pro['email'] ?>" title="L'adresse mail doit comporter un '@' et un '.'"
                    placeholder="exemple@gmail.com"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="email" id="email"
                    name="email">

                <label class="text-h3" for="num_tel">Numéro de téléphone</label>
                <input value="<?php echo $pro['tel'] ?>" pattern="^0\d( \d{2}){4}"
                    class="border-2 border-secondary p-2 bg-white max-w-36 h-12 mb-3 rounded-lg" id="num_tel"
                    name="num_tel" title="Le numéro doit commencer par un 0 et comporter 10 chiffres"
                    placeholder="01 23 45 67 89">

                <input type="submit" id="save1" href="" value="Enregistrer les modifications"
                    class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                    disabled>
                </input>
            </form>

            <?php
            if ($pro['data']['type'] == 'prive') { ?>
                <hr class="mb-8">
                <form action="" class="flex flex-col" method="post">

                    <label class="text-h3" for="iban">IBAN</label>
                    <input value="<?php if (isset($rib) && $rib != null) {
                        echo extraireIbanDepuisRib($rib);
                    } ?>" class="border-2 border-secondary p-2 bg-white max-w-80 h-12 mb-3 rounded-lg" type="text"
                        id="iban" name="iban" pattern="^(FR)\d{2}( \d{4}){5} \d{3}$"
                        placeholder="FRXX XXXX XXXX XXXX XXXX XXXX XXX" title="Format : FRXX XXXX XXXX XXXX XXXX XXXX XXX ">

                    <input type="submit" id="save2" href="" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                        disabled>
                    </input>
                </form>

                <hr class="mb-8">

                <form action="" class="flex flex-col" method="post">

                    <label class="text-h3" for="num_siren">Numéro SIRET</label>
                    <input id="num_siren" name="num_siren" pattern="^\d{14}$"
                        title="Le numéro SIRET doit être composé de 14 chiffres" placeholder="Ex: 12345678901234"
                        value="<?php echo $pro['data']['numero_siren'] ?>"
                        class="border-2 border-secondary p-2 bg-white max-w-44 h-12 mb-3 rounded-lg">

                    <input type="submit" id="save3" href="" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                        disabled>
                    </input>

                </form>

                <?php
            } else {
                ?>
                <hr class="mb-8">
                <form action="" class="flex flex-col" method="post">
                    <label class="text-h3" for="type_orga">Type d'organisation</label>
                    <input value="<?php echo $pro['data']['type_orga'] ?>"
                        class="border-2 border-secondary p-2 bg-white max-w-36 h-12 mb-3 rounded-lg" type="text"
                        id="type_orga" name="type_orga">

                    <input type="submit" id="save4" href="" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                        disabled>
                    </input>
                </form>
                <?php
            } ?>

            <hr class="mb-8">

            <a href="/scripts/delete.php" onclick="return confirmDelete()"
                class="mx-auto max-w-[23rem] w-full h-12 p-1 font-bold text-small text-center text-wrap text-rouge-logo bg-transparent rounded-lg flex items-center justify-center border border-rouge-logo hover:text-white hover:bg-red-600 hover:border-red-600 focus:scale-[0.97]">
                Supprimer mon compte
            </a>
        </div>
    </main>


    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const iban = document.getElementById("iban");
        const siren = document.getElementById("num_siren");
        const type_orga = document.getElementById("type_orga");

        const initialValues = {
            email: document.getElementById("email").value,
            num_tel: document.getElementById("num_tel").value,
        };

        if (iban) {
            initialValues.iban = iban.value;
        }
        if (siren) {
            initialValues.siren = siren.value;
        }
        if (type_orga) {
            initialValues.type_orga = type_orga.value;
        }

        function activeSave1() {
            const save1 = document.getElementById("save1");
            const email = document.getElementById("email").value;
            const num_tel = document.getElementById("num_tel").value;

            if (email !== initialValues.email || num_tel !== initialValues.num_tel) {
                save1.disabled = false;
                save1.classList.remove("opacity-50");
                save1.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save1.disabled = true;
                save1.classList.add("opacity-50");
                save1.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        function activeSave2() {
            const save2 = document.getElementById("save2");
            const iban = document.getElementById("iban").value;

            if (iban !== initialValues.iban) {
                save2.disabled = false;
                save2.classList.remove("opacity-50");
                save2.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save2.disabled = true;
                save2.classList.add("opacity-50");
                save2.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        function activeSave3() {
            const save3 = document.getElementById("save3");
            const siren = document.getElementById("num_siren").value;

            if (siren !== initialValues.siren) {
                save3.disabled = false;
                save3.classList.remove("opacity-50");
                save3.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save3.disabled = true;
                save3.classList.add("opacity-50");
                save3.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        function activeSave4() {
            const save4 = document.getElementById("save4");
            const type_orga = document.getElementById("type_orga").value;

            if (type_orga !== initialValues.type_orga) {
                save4.disabled = false;
                save4.classList.remove("opacity-50");
                save4.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save4.disabled = true;
                save4.classList.add("opacity-50");
                save4.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("email").addEventListener("input", activeSave1);
        document.getElementById("num_tel").addEventListener("input", activeSave1);
        if (iban) {
            document.getElementById("iban").addEventListener("input", activeSave2);
        }
        if (siren) {
            document.getElementById("num_siren").addEventListener("input", activeSave3);
        }
        if (type_orga) {
            document.getElementById("type_orga").addEventListener("input", activeSave4);
        }

    });

    function formatSiren(input) {
        // Supprime tout ce qui n'est pas un chiffre
        let value = input.value.replace(/\D/g, '');

        // Limite à 14 caractères (9 pour le SIREN + 5 pour les caractères supplémentaires)
        value = value.substring(0, 14);

        // Ajoute les espaces tous les 3 chiffres pour les trois premiers groupes
        let formatted = value
            .replace(/(\d{3})(\d)/, '$1 $2') // Ajoute un espace après les 3 premiers chiffres
            .replace(/(\d{3}) (\d{3})(\d)/, '$1 $2 $3') // Ajoute un espace après les 6 premiers chiffres
            .replace(/(\d{3}) (\d{3}) (\d{3})(\d+)/, '$1 $2 $3 $4'); // Le reste (5 derniers caractères sans espace)

        // Met à jour la valeur de l'input avec le format correct
        input.value = formatted;
    }
</script>