<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$pro = verifyPro();

if (isset($_POST['nom'])) {
    if ($pro['data']['type'] == 'prive') {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
        $controllerProPrive = new ProPriveController();
        $controllerProPrive->updateProPrive($pro['id_compte'], false, false, false, false, $_POST['nom'], false);
        unset($_POST['nom']);
    } else {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
        $controllerProPublic = new ProPublicController();
        $controllerProPublic->updateProPublic($pro['id_compte'], false, false, false, false, $_POST['nom'], false);
        unset($_POST['nom']);
    }
}

if (isset($_POST['adresse']) || isset($_POST['complement']) || isset($_POST['code_postal']) || isset($_POST['ville'])) {
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
    $controllerAdresse = new AdresseController();
    $adresse = $controllerAdresse->getInfosAdresse($pro['id_adresse']);

    $numero = false;
    $odonyme = false;
    $complement = false;
    $code_postal = false;
    $ville = false;

    if (isset($_POST['adresse'])) {
        $adresse = $_POST['adresse'];
        $adresse = explode(" ", $adresse);
        $numero = $adresse[0];
        $odonyme = implode(" ", array_slice($adresse, 1));
        unset($_POST['adresse']);
    }
    if (isset($_POST['complement'])) {
        $complement = $_POST['complement'];
        unset($_POST['ville']);
    }
    if (isset($_POST['code_postal'])) {
        $code_postal = $_POST['code_postal'];
        unset($_POST['code_postal']);
    }
    if (isset($_POST['ville'])) {
        $ville = $_POST['ville'];
        unset($_POST['ville']);
    }

    $controllerAdresse->updateAdresse($pro['id_adresse'], $code_postal, $ville, $numero, $odonyme, $complement);
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

    <title>Profil du compte - Professionnel - PACT</title>
</head>
<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
$controllerAdresse = new AdresseController();
$adresse = $controllerAdresse->getInfosAdresse($pro['id_adresse']);
?>

<body class="min-h-screen flex flex-col justify-between">
    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <div class="flex w-full items-center">
            <a href="#" onclick="toggleMenu()" class="mr-4 flex gap-4 items-center hover:text-primary duration-100">
                <i class="text-3xl fa-solid fa-bars"></i>
            </a>
            <p class="text-h2">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="/pro/compte/profil" class="underline">Profil</a>
            </p>
        </div>
    </header>
    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="max-w-[44rem] m-auto flex flex-col">
            <p class="text-h1 mb-4">Informations publiques</p>

            <form action="" class="flex flex-col" method="post">
                <label class="text-h3"
                    for="nom"><?php if ($pro['data']['type'] == 'prive') { ?>Dénomination<?php } else { ?>Nom
                        de l'organisation<?php } ?></label>
                <input value="<?php echo $pro['nom_pro'] ?>"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="nom"
                    name="nom" maxlength="255">

                <input type="submit" id="save1" href="" value="Enregistrer les modifications"
                    class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                    disabled>
                </input>
            </form>

            <hr class="mb-8">

            <form action="" class="flex flex-col" method="post">
                <label class="text-h3" for="adresse">Adresse postale</label>
                <input value="<?php echo $adresse['numero'] . " " . $adresse['odonyme'] ?>"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="adresse"
                    name="adresse" maxlength="255"">
                
                <label class=" text-h3" for="complement">Complément adresse postale</label>
                <input value="<?php echo $adresse['complement'] ?>"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text"
                    id="complement" name="complement" maxlength="255"">
                    
                <div class=" flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-32">
                    <label class="text-h3" for="code">Code postal</label>
                    <input value="<?php echo $adresse['code_postal'] ?>"
                        class="border-2 border-secondary p-2 text-right bg-white max-w-32 h-12 mb-3 rounded-lg"
                        type="text" id="code" name="code" minlength="5" maxlength="5">
                </div>
                <div class="w-full">
                    <label class="text-h3" for="ville">Ville</label>
                    <input value="<?php echo $adresse['ville'] ?>"
                        class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text"
                        id="ville" name="ville" maxlength="50">
                </div>
        </div>

        <input type="submit" id="save2" href="" value="Enregistrer les modifications"
            class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
            disabled>
        </input>
        </form>

        <hr class="mb-8">

        <div class="max-w-[23rem] mx-auto">
            <a href="/pro/compte/profil/avis"
                class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center mb-8 px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-egg"></i>
                <div class="w-full">
                    <p class="text-h2">Avis</p>
                    <p class="text-small">Consulter l’ensemble des avis sur mes offres de la PACT.</p>
                </div>
            </a>
        </div>
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
        const initialValues = {
            nom: document.getElementById("nom").value,
            adresse: document.getElementById("adresse").value,
            complement: document.getElementById("complement").value,
            code: document.getElementById("code").value,
            ville: document.getElementById("ville").value,
        };

        function activeSave1() {
            const save1 = document.getElementById("save1");
            const nom = document.getElementById("nom").value;

            if (nom !== initialValues.nom) {
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
            const adresse = document.getElementById("adresse").value;
            const complement = document.getElementById("complement").value;
            const code = document.getElementById("code").value;
            const ville = document.getElementById("ville").value;

            if (adresse !== initialValues.adresse || complement !== initialValues.complement || code !== initialValues.code || ville !== initialValues.ville) {
                save2.disabled = false;
                save2.classList.remove("opacity-50");
                save2.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save2.disabled = true;
                save2.classList.add("opacity-50");
                save2.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("nom").addEventListener("input", activeSave1);
        document.getElementById("adresse").addEventListener("input", activeSave2);
        document.getElementById("complement").addEventListener("input", activeSave2);
        document.getElementById("code").addEventListener("input", activeSave2);
        document.getElementById("ville").addEventListener("input", activeSave2);
    });
</script>