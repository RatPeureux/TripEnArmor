<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Obtenir les informations du pro
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();

// Controllers
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
$controllerAdresse = new AdresseController();
$adresse = $controllerAdresse->getInfosAdresse($pro['id_adresse']);

if (isset($_POST['nom']) && !empty($_POST['nom'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

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

// Récupérer les valeurs des différents champs
if (isset($_POST['user_input_autocomplete_address']) || isset($_POST['complement']) || isset($_POST['postal_code']) || isset($_POST['locality'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $numero = null;
    $odonyme = null;
    $complement = null;
    $code_postal = false;
    $ville = false;

    if (!empty($_POST['user_input_autocomplete_address'])) {
        $adresse = $_POST['user_input_autocomplete_address'];
        // Utiliser une expression régulière pour extraire le numéro et l'odonyme
        if (preg_match('/^(\d+)\s+(.*)$/', $adresse, $matches)) {
            $numero = $matches[1];
            $odonyme = $matches[2];
        }
        // Si l'adresse ne correspond pas au format attendu, retourner des valeurs par défaut
        else {
            $numero = null;
            $odonyme = $adresse;
        }
        unset($_POST['user_input_autocomplete_address']);
    }
    if (!empty($_POST['complement'])) {
        $complement = $_POST['complement'];
        unset($_POST['complement']);
    }
    if (!empty($_POST['postal_code'])) {
        $code_postal = $_POST['postal_code'];
        unset($_POST['postal_code']);
    }
    if (!empty($_POST['locality'])) {
        $locality = $_POST['locality'];
        unset($_POST['locality']);
    }
    if (!empty($_POST['lat'])) {
        $lat = $_POST['lat'];
        unset($_POST['lat']);
    }
    if (!empty($_POST['lng'])) {
        $lng = $_POST['lng'];
        unset($_POST['lng']);
    }

    $controllerAdresse->updateAdresse($pro['id_adresse'], $code_postal, $ville, $numero, $odonyme, $complement, $lat, $lng);
}

$pro = verifyPro();
$adresse = $controllerAdresse->getInfosAdresse($pro['id_adresse']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- POUR LEAFLET ET L'AUTOCOMPLETION -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />

    <!-- NOS FICHIERS -->
    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js"></script>

    <title>Profil du compte - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <!-- Map à afficher pour choisir l'adresse -->
    <div id="map-container" class="z-30 fixed top-0 left-0 h-full w-full flex hidden items-center justify-center">
        <!-- Background blur -->
        <div class="fixed top-0 left-0 w-full h-full bg-blur/25 backdrop-blur"
            onclick="document.getElementById('map-container').classList.add('hidden');">
        </div>

        <div id="map" class="border border-black max-w-[500px] max-h-[500px] h-full w-full"></div>
    </div>

    <!-- LEAFLET JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- GEOSEARCH JS -->
    <script src="https://unpkg.com/leaflet-geosearch@latest/dist/bundle.min.js"></script>
    <!-- CONFIGURER LA MAP -->
    <script src="/scripts/selectOnMap.js" type="module"></script>

    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="m-auto flex flex-col">
            <p class="text-xl p-4">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="/pro/compte/profil" class="underline">Profil</a>
            </p>

            <hr class="mb-8">

            <p class="text-3xl mb-4">Informations publiques</p>

            <form action="/pro/compte/profil/" class="flex flex-col" method="post">
                <label class="text-xl"
                    for="nom"><?php if ($pro['data']['type'] == 'prive') { ?>Dénomination<?php } else { ?>Nom
                        de l'organisation<?php } ?></label>
                <input value="<?php echo $pro['nom_pro'] ?>"
                    class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 " type="text" id="nom"
                    name="nom">

                <input type="submit" id="save1" value="Enregistrer les modifications"
                    class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                    disabled>

            </form>

            <hr class="mb-8">

            <form action="/pro/compte/profil/" class="flex flex-col" method="post">

                <!-- Champs pour l'adresse -->
                <p id="select-on-map"
                    class="p-2 border border-black self-start cursor-pointer hover:border-secondary hover:text-white hover:bg-secondary"
                    onclick="showMap();">Choisir l'adresse</p>

                <!-- Champs cachés pour les coordonnées -->
                <input class='hidden' id='lat' name='lat' value="<?php echo $adresse['lat'] ?? '0' ?>">
                <input class='hidden' id='lng' name='lng' value="<?php echo $adresse['lng'] ?? '0' ?>">

                <label class="text-xl" for="user_input_autocomplete_address">Adresse postale</label>
                <input value="<?php echo $adresse['numero'] . ' ' . $adresse['odonyme'] ?>"
                    class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 " type="text"
                    id="user_input_autocomplete_address" name="user_input_autocomplete_address">

                <label class=" text-xl" for="complement">Complément adresse postale</label>
                <input value="<?php echo $adresse['complement'] ?>"
                    class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 " type="text" id="complement"
                    name="complement">

                <div class="flex gap-8 items-center">
                    <div class="flex flex-col">
                        <label class="text-lg" for="postal_code">Code postal</label>
                        <input type="text" id="postal_code" name="postal_code"
                            value="<?php echo $adresse['code_postal'] ?>"
                            class="w-[70px] text-center border border-secondary p-2 text-sm bg-white max-w-32 h-12 mb-3 "
                            pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Format : 12345" placeholder="12345">
                    </div>

                    <div>
                        <label class="text-lg" for="locality">Ville</label>
                        <input id="locality" name="locality" value="<?php echo $adresse['ville'] ?>"
                            pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$"
                            title="Saisir votre ville" placeholder="Rennes"
                            class="border border-secondary text-sm p-2 bg-white w-full h-12 mb-3 ">
                    </div>
                </div>

                <input type="submit" id="save2" value="Enregistrer les modifications"
                    class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                    disabled>

            </form>

            <hr class="mb-8">

            <div class="max-w-[23rem] mx-auto">
                <a href="/pro/compte/profil/avis"
                    class="cursor-pointer w-full bg-base100 space-x-8 flex items-center px-8 py-4">
                    <i class="w-[50px] text-center text-4xl fa-solid fa-egg"></i>
                    <div class="w-full">
                        <p class="text-lg">Avis</p>
                        <p class="text-sm">Consulter l’ensemble des avis sur mes offres de la PACT.</p>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>

    <script>
        const inputs = {
            nom: document.getElementById("nom"),
            adresse: document.getElementById("user_input_autocomplete_address"),
            complement: document.getElementById("complement"),
            code: document.getElementById("postal_code"),
            ville: document.getElementById("locality"),
        };

        const save1 = document.getElementById("save1");
        const save2 = document.getElementById("save2");

        triggerSaveBtnOnInputsChange([inputs.nom], save1);
        triggerSaveBtnOnInputsChange([inputs.adresse, inputs.complement, inputs.code, inputs.ville], save2);
    </script>

</body>

</html>