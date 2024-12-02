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

include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
$controllerAdresse = new AdresseController();
$adresse = $controllerAdresse->getInfosAdresse($membre['id_adresse']);

if (isset($_POST['nom']) || isset($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['prenom'])) {
    $prenom = false;
    $nom = false;
    if (isset($_POST['nom'])) {
        $prenom = $_POST['nom'];
        unset($_POST['nom']);
    }
    if (isset($_POST['prenom'])) {
        $nom = $_POST['prenom'];
        unset($_POST['prenom']);
    }
    $controllerMembre->updateMembre($membre['id_compte'], false, false, false, false, false, $nom, $prenom);
}

if ((isset($_POST['email']) && !empty($_POST['email'])) || (isset($_POST['num_tel']) && !empty($_POST['num_tel']) && strlen($_POST['num_tel']) > 14)) {
    $email = false;
    $num_tel = false;
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        unset($_POST['email']);
    }
    if (isset($_POST['num_tel']) && strlen($_POST['num_tel']) > 14) {
        $num_tel = $_POST['num_tel'];
        unset($_POST['num_tel']);
    }
    $controllerMembre->updateMembre($membre['id_compte'], $email, false, $num_tel, false, false, false, false);
}
if ((isset($_POST['adresse']) && !empty($_POST['adresse'])) || isset($_POST['complement']) || (isset($_POST['code']) && !empty($_POST['code'])) || (isset($_POST['ville']) && !empty($_POST['ville']))) {
    $numero = false;
    $odonyme = false;
    $complement = false;
    $code = false;
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
        unset($_POST['complement']);
    }
    if (isset($_POST['code'])) {
        $code = $_POST['code'];
        unset($_POST['code']);
    }
    if (isset($_POST['ville'])) {
        $ville = $_POST['ville'];
        unset($_POST['ville']);
    }

    $controllerAdresse->updateAdresse($membre['id_adresse'], $code, $ville, $numero, $odonyme, $complement);
}

$adresse = $controllerAdresse->getInfosAdresse($membre['id_adresse']);
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
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Paramètres du compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">
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

    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/adresse_controller.php';
    $controllerAdresse = new AdresseController();
    $adresse = $controllerAdresse->getInfosAdresse($membre['id_adresse']);
    ?>

    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <div class="flex w-full items-center">
            <a href="" onclick="toggleMenu()" class="mr-4 md:hidden">
                <i class="text-3xl fa-solid fa-bars"></i>
            </a>
            <p class="text-h2">
                <a href="/compte">Mon compte</a>
                >
                <a href="/compte/parametres" class="underline">Paramètres</a>
            </p>
        </div>
    </header>

    <main class="w-full flex justify-center grow">
        <div class="max-w-[1280px] w-full p-2 flex justify-center">
            <div id="menu">
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu.php';
                ?>
            </div>

            <div class="flex flex-col md:mx-10 grow">
                <p class="text-h1 mb-4">Informations privées</p>

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-h3" for="prenom">Prénom</label>
                        <input value="<?php echo $membre['prenom'] ?>"
                            class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text"
                            id="prenom" name="prenom" maxlength="50">
                    </div>
                    <div class="w-full">
                        <label class="text-h3" for="nom">Nom</label>
                        <input value="<?php echo $membre['nom'] ?>"
                            class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text"
                            id="nom" name="nom" maxlength="50">
                    </div>
                </div>

                <button id="save1"
                    class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                    disabled>
                    Enregistrer les modifications
                </button>

                <hr class="mb-8">

                <label class="text-h3" for="email">Adresse mail</label>
                <input value="<?php echo $membre['email'] ?>"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="email" id="email"
                    name="email" maxlength="255">

                <label class="text-h3" for="num_tel">Numéro de téléphone</label>
                <input value="<?php echo $membre['num_tel'] ?>"
                    class="border-2 border-secondary p-2 bg-white max-w-36 h-12 mb-3 rounded-lg" type="tel" id="num_tel"
                    name="num_tel" minlength="14" maxlength="14">

                <button id="save2"
                    class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                    disabled>
                    Enregistrer les modifications
                </button>

                <hr class="mb-8">

                <label class="text-h3" for="adresse">Adresse postale</label>
                <input value="<?php echo $adresse['numero'] . " " . $adresse['odonyme'] ?>"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="adresse"
                    name="adresse" maxlength="255"">

            <label class=" text-h3" for="complement">Complément adresse postale</label>
                <input value="<?php echo $adresse['complement']; ?>"
                    class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text"
                    id="complement" name="complement" maxlength="255"">

            <div class=" flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-32">
                    <label class="text-h3" for="code">Code postal</label>
                    <input value="<?php echo $adresse['code_postal']; ?>"
                        class="border-2 border-secondary p-2 text-right bg-white max-w-32 h-12 mb-3 rounded-lg"
                        type="text" id="code" name="code" minlength="5" maxlength="5">
                </div>
                <div class="w-full">
                    <label class="text-h3" for="ville">Ville</label>
                    <input value="<?php echo $adresse['ville']; ?>"
                        class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text"
                        id="ville" name="ville" maxlength="50">
                </div>
            </div>

            <button id="save3"
                class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                disabled>
                Enregistrer les modifications
            </button>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer.php';
    ?>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const initialValues = {
            prenom: document.getElementById("prenom").value,
            nom: document.getElementById("nom").value,
            email: document.getElementById("email").value,
            num_tel: document.getElementById("num_tel").value,
            adresse: document.getElementById("adresse").value,
            complement: document.getElementById("complement").value,
            code: document.getElementById("code").value,
            ville: document.getElementById("ville").value,
        };

        function activeSave1() {
            const save1 = document.getElementById("save1");
            const prenom = document.getElementById("prenom").value;
            const nom = document.getElementById("nom").value;

            if (prenom !== initialValues.prenom || nom !== initialValues.nom) {
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
            const email = document.getElementById("email").value;
            const num_tel = document.getElementById("num_tel").value;

            if (email !== initialValues.email || num_tel !== initialValues.num_tel) {
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
            const adresse = document.getElementById("adresse").value;
            const complement = document.getElementById("complement").value;
            const code = document.getElementById("code").value;
            const ville = document.getElementById("ville").value;

            if (adresse !== initialValues.adresse || complement !== initialValues.complement || code !== initialValues.code || ville !== initialValues.ville) {
                save3.disabled = false;
                save3.classList.remove("opacity-50");
                save3.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save3.disabled = true;
                save3.classList.add("opacity-50");
                save3.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("prenom").addEventListener("input", activeSave1);
        document.getElementById("nom").addEventListener("input", activeSave1);
        document.getElementById("email").addEventListener("input", activeSave2);
        document.getElementById("num_tel").addEventListener("input", activeSave2);
        document.getElementById("adresse").addEventListener("input", activeSave3);
        document.getElementById("complement").addEventListener("input", activeSave3);
        document.getElementById("code").addEventListener("input", activeSave3);
        document.getElementById("ville").addEventListener("input", activeSave3);
    });
</script>