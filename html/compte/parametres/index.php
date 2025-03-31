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
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
$controllerAdresse = new AdresseController();
$adresse = $controllerAdresse->getInfosAdresse($membre['id_adresse']);

if (isset($_POST['nom']) || isset($_POST['prenom'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $prenom = false;
    $nom = false;
    if (!empty($_POST['nom'])) {
        $prenom = $_POST['nom'];
        unset($_POST['nom']);
    }
    if (!empty($_POST['prenom'])) {
        $nom = $_POST['prenom'];
        unset($_POST['prenom']);
    }
    $controllerMembre->updateMembre($membre['id_compte'], false, false, false, false, false, $nom, $prenom);
}

if (isset($_POST['email']) || isset($_POST['num_tel'])) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $email = false;
    $num_tel = false;
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        unset($_POST['email']);
    }
    if (strlen($_POST['num_tel']) > 14) {
        $num_tel = $_POST['num_tel'];
        unset($_POST['num_tel']);
    }
    $controllerMembre->updateMembre($membre['id_compte'], $email, false, $num_tel, false, false, false, false);
}

if ((isset($_POST['user_input_autocomplete_address']) && !empty($_POST['user_input_autocomplete_address'])) || isset($_POST['complement']) || (isset($_POST['postal_code']) && !empty($_POST['postal_code'])) || (isset($_POST['locality']) && !empty($_POST['locality']))) {
    $_SESSION['message_pour_notification'] = 'Informations mises à jour';

    $numero = null;
    $odonyme = null;
    $complement = null;
    $code = false;
    $ville = false;
    $lat = null;
    $lng = null;

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
    if (isset($_POST['complement'])) {
        $complement = $_POST['complement'];
        unset($_POST['complement']);
    }
    if (!empty($_POST['postal_code'])) {
        $code = $_POST['postal_code'];
        unset($_POST['postal_code']);
    }
    if (!empty($_POST['locality'])) {
        $ville = $_POST['locality'];
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

    $controllerAdresse->updateAdresse($membre['id_adresse'], $code, $ville, $numero, $odonyme, $complement, $lat, $lng);
}

// Rafraîchier les informations avec celles mises à jour
$membre = verifyMember();
$adresse = $controllerAdresse->getInfosAdresse($membre['id_adresse']);
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

    <title>Paramètres du compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Map à afficher pour Trouver mon adresse -->
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
                    <a href="/compte/parametres" class="underline">Paramètres</a>
                </p>

                <hr class="mb-8">

                <p class="text-2xl">Informations privées</p>

                <form action="/compte/parametres/" class="flex flex-col" method="post">
                    <div class="flex flex-nowrap space-x-3 mb-3">
                        <div class="w-full">
                            <label class="text-lg" for="prenom">Prénom</label>
                            <input value="<?php echo $membre['prenom'] ?>"
                                class="border text-sm border-secondary p-2 bg-white w-full h-12  " type="text"
                                id="prenom" name="prenom">
                        </div>
                        <div class="w-full">
                            <label class="text-lg" for="nom">Nom</label>
                            <input value="<?php echo $membre['nom'] ?>"
                                class="border text-sm border-secondary p-2 bg-white w-full h-12  " type="text"
                                id="nom" name="nom">
                        </div>
                    </div>

                    <input type="submit" id="save1" value="Enregistrer les modifications"
                        class="cursor-pointer self-end opacity-50 max-w-sm mb-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>

                </form>

                <hr class="mb-8">

                <form action="/compte/parametres/" class="flex flex-col gap-2" method="post">
                    <label class="text-lg" for="email">Adresse mail</label>
                    <input value="<?php echo $membre['email'] ?>" placeholder="exemple@gmail.com"
                        title="L'adresse mail doit comporter un '@' et un '.'"
                        class="border border-secondary p-2 bg-white w-full h-12  " type="email" id="email"
                        name="email">

                    <label class="text-lg" for="num_tel">Numéro de téléphone</label>
                    <input id="num_tel" name="num_tel" value="<?php echo $membre['tel'] ?>"
                        class="border border-secondary p-2 bg-white max-w-36 h-12  " pattern="^0\d( \d{2}){4}"
                        title="Le numéro de téléphone doit commencer par un 0 et comporter 10 chiffres"
                        placeholder="01 23 45 67 89">

                    <input type="submit" id="save2" value="Enregistrer les modifications"
                        class="cursor-pointer self-end opacity-50 max-w-sm mb-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>

                </form>

                <hr class="mb-8">

                <!-- Champs pour l'adresse -->
                <form action="/compte/parametres" class="flex flex-col gap-2" method="post">

                    <!-- Bouton de sélection sur la carte -->
                    <p id="select-on-map"
                        class="text-sm p-2 border rounded-full text-center border-black self-start cursor-pointer hover:border-secondary hover:text-white hover:bg-secondary"
                        onclick="showMap();">Trouver mon adresse</p>

                    <!-- Champs cachés pour les coordonnées -->
                    <input class='hidden' id='lat' name='lat' value="<?php echo $adresse['lat'] ?? '0' ?>">
                    <input class='hidden' id='lng' name='lng' value="<?php echo $adresse['lng'] ?? '0' ?>">

                    <label class="text-lg" for="user_input_autocomplete_address">Adresse postale</label>
                    <input value="<?php echo $adresse['numero'] . " " . $adresse['odonyme'] ?>"
                        class="border border-secondary text-sm p-2 bg-white w-full h-12  " type="text"
                        id="user_input_autocomplete_address" name="user_input_autocomplete_address">

                    <label class="text-lg" for="complement">Complément adresse postale</label>
                    <input value="<?php echo $adresse['complement'] ?>"
                        class="border border-secondary text-sm p-2 bg-white w-full h-12  " type="text"
                        id="complement" name="complement">

                    <div class="flex gap-8 items-center">
                        <div class="flex flex-col gap-2">
                            <label class="text-lg" for="postal_code">Code postal</label>
                            <input type="text" id="postal_code" name="postal_code"
                                value="<?php echo $adresse['code_postal'] ?>"
                                class="w-[70px] text-center border border-secondary p-2 text-sm bg-white max-w-32 h-12  "
                                pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Format : 12345"
                                placeholder="12345">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-lg" for="locality">Ville</label>
                            <input id="locality" name="locality" value="<?php echo $adresse['ville'] ?>"
                                pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$"
                                title="Saisir votre ville" placeholder="Rennes"
                                class="border border-secondary text-sm p-2 bg-white w-full h-12 ">
                        </div>
                    </div>

                    <input type="submit" id="save3" value="Enregistrer les modifications"
                        class="cursor-pointer self-end opacity-50 max-w-sm mb-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                </form>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = {
                prenom: document.getElementById("prenom"),
                nom: document.getElementById("nom"),
                email: document.getElementById("email"),
                num_tel: document.getElementById("num_tel"),
                adresse: document.getElementById("user_input_autocomplete_address"),
                complement: document.getElementById("complement"),
                code: document.getElementById("postal_code"),
                ville: document.getElementById("locality"),
            };

            const save1 = document.getElementById("save1");
            const save2 = document.getElementById("save2");
            const save3 = document.getElementById("save3");

            triggerSaveBtnOnInputsChange([inputs.prenom, inputs.nom], save1);
            triggerSaveBtnOnInputsChange([inputs.email, inputs.num_tel], save2);
            triggerSaveBtnOnInputsChange([inputs.adresse, inputs.complement, inputs.code, inputs.ville], save3);
        });
    </script>

</body>

</html>