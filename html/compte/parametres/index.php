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

if (isset($_POST['nom']) || isset($_POST['prenom'])) {
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
if ((isset($_POST['adresse']) && !empty($_POST['adresse'])) || isset($_POST['complement']) || (isset($_POST['postal_code']) && !empty($_POST['postal_code'])) || (isset($_POST['ville']) && !empty($_POST['ville']))) {
    $numero = null;
    $odonyme = null;
    $complement = null;
    $code = false;
    $ville = false;

    if (!empty($_POST['adresse'])) {
        $adresse = $_POST['adresse'];
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
        unset($_POST['adresse']);
    }
    if (isset($_POST['complement'])) {
        $complement = $_POST['complement'];
        unset($_POST['complement']);
    }
    if (!empty($_POST['postal_code'])) {
        $code = $_POST['postal_code'];
        unset($_POST['postal_code']);
    }
    if (!empty($_POST['ville'])) {
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
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js"></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Paramètres du compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

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
                <p class="text-h3 p-4">
                    <a href="/compte">Mon compte</a>
                    >
                    <a href="/compte/parametres" class="underline">Paramètres</a>
                </p>

                <hr class="mb-8">

                <p class="text-h2 mb-4">Informations privées</p>

                <form action="" class="flex flex-col" method="post">
                    <div class="flex flex-nowrap space-x-3 mb-1.5">
                        <div class="w-full">
                            <label class="text-h4" for="prenom">Prénom</label>
                            <input value="<?php echo $membre['prenom'] ?>"
                                class="border text-small border-secondary p-2 bg-white w-full h-12 mb-3 " type="text"
                                id="prenom" name="prenom">
                        </div>
                        <div class="w-full">
                            <label class="text-h4" for="nom">Nom</label>
                            <input value="<?php echo $membre['nom'] ?>"
                                class="border text-small border-secondary p-2 bg-white w-full h-12 mb-3 " type="text"
                                id="nom" name="nom">
                        </div>
                    </div>

                    <input type="submit" id="save1" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-small text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                    </input>
                </form>


                <hr class="mb-8">
                <form action="" class="flex flex-col" method="post">

                    <label class="text-h4" for="email">Adresse mail</label>
                    <input value="<?php echo $membre['email'] ?>" placeholder="exemple@gmail.com"
                        title="L'adresse mail doit comporter un '@' et un '.'"
                        class="border border-secondary p-2 bg-white w-full h-12 mb-3 " type="email" id="email"
                        name="email">

                    <label class="text-h4" for="num_tel">Numéro de téléphone</label>
                    <input id="num_tel" name="num_tel" value="<?php echo $membre['tel'] ?>"
                        class="border border-secondary p-2 bg-white max-w-36 h-12 mb-3 " pattern="^0\d( \d{2}){4}"
                        title="Le numéro de téléphone doit commencer par un 0 et comporter 10 chiffres"
                        placeholder="01 23 45 67 89">

                    <input type="submit" id="save2" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-small text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                    </input>
                </form>

                <hr class="mb-8">

                <form action="" class="flex flex-col" method="post">
                    <label class="text-h4" for="adresse">Adresse postale</label>
                    <input value="<?php echo $adresse['numero'] . " " . $adresse['odonyme'] ?>"
                        class="border border-secondary text-small p-2 bg-white w-full h-12 mb-3 " type="text"
                        id="adresse" name="adresse">

                    <label class="text-h4" for="complement">Complément adresse postale</label>
                    <input value="<?php echo $adresse['complement'] ?>"
                        class="border border-secondary text-small p-2 bg-white w-full h-12 mb-3 " type="text"
                        id="complement" name="complement">

                    <div class="flex flex-nowrap space-x-3 mb-1.5">
                        <div class="w-32">
                            <label class="text-h4" for="postal_code">Code postal</label>
                            <input id="postal_code" name="postal_code" value="<?php echo $adresse['code_postal'] ?>"
                                class="border border-secondary p-2 text-small text-right bg-white max-w-32 h-12 mb-3 "
                                pattern="^(0[1-9]|[1-8]\d|9[0-5]|2A|2B)\d{3}$" title="Format : 12345"
                                placeholder="12345">
                        </div>
                        <div class="w-full">
                            <label class="text-h4" for="locality">Ville</label>
                            <input id="locality" name="locality" value="<?php echo $adresse['ville'] ?>"
                                pattern="^[a-zA-Zéèêëàâôûç\-'\s]+(?:\s[A-Z][a-zA-Zéèêëàâôûç\-']+)*$"
                                title="Saisir votre ville" placeholder="Rennes"
                                class="border border-secondary text-small p-2 bg-white w-full h-12 mb-3 ">
                        </div>
                    </div>

                    <input type="submit" id="save3" value="Enregistrer les modifications"
                        class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-small text-white bg-primary  border border-transparent rounded-full"
                        disabled>
                    </input>

                    <hr class="hidden mb-8">

                    <a href="/scripts/delete.php" class="hidden" onclick="return confirmDelete()"
                        class="mx-auto max-w-[23rem] w-full h-12 p-1  text-small text-center text-wrap text-rouge-logo bg-transparent  flex items-center justify-center border border-rouge-logo hover:text-white hover:bg-red-600 hover:border-red-600 focus:scale-[0.97]">
                        Supprimer mon compte
                    </a>
                </form>
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
            prenom: document.getElementById("prenom").value,
            nom: document.getElementById("nom").value,
            email: document.getElementById("email").value,
            num_tel: document.getElementById("num_tel").value,
            adresse: document.getElementById("adresse").value,
            complement: document.getElementById("complement").value,
            code: document.getElementById("postal_code").value,
            ville: document.getElementById("locality").value,
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
            const code = document.getElementById("postal_code").value;
            const ville = document.getElementById("locality").value;

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
        document.getElementById("postal_code").addEventListener("input", activeSave3);
        document.getElementById("locality").addEventListener("input", activeSave3);
    });
</script>