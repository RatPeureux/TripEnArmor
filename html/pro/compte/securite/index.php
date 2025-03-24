<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$pro = verifyPro();

if (isset($_POST['mdp'])) {
    if ($pro['data']['type'] == 'prive') {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
        $controllerProPrive = new ProPriveController();
        $currentPassword = $controllerProPrive->getMdpProPrive($pro['id_compte']);
    } else {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
        $controllerProPublic = new ProPublicController();
        $currentPassword = $controllerProPublic->getMdpProPublic($pro['id_compte']);
    }

    if (password_verify($_POST['mdp'], $currentPassword)) {
        $mdp = password_hash($_POST['newMdp'], PASSWORD_DEFAULT);
        if ($pro['data']['type'] == 'prive') {
            $controllerProPrive->updateProPrive($pro['id_compte'], false, $mdp, false, false, false, false);
        } else {
            $controllerProPublic->updateProPublic($pro['id_compte'], false, $mdp, false, false, false, false);
        } ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var successMessage = document.getElementById('success-message');
                successMessage.textContent = 'Le mot de passe a bien été modifié.';
                setTimeout(function () {
                    successMessage.textContent = '';
                }, 7500);
            });
        </script>
    <?php } else { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var errorMessage = document.getElementById('error-message');
                errorMessage.textContent = 'Le mot de passe actuel est incorrect.';
                setTimeout(function () {
                    errorMessage.textContent = '';
                }, 7500);
            });
        </script>
    <?php }

    unset($_POST['mdp']);
    unset($_POST['newMdp']);
    unset($_POST['newConfMdp']);
    $pro = verifyPro();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <!-- NOS FICHIERS -->
    <script type="module" src="/scripts/main.js"></script>
    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">

    <!-- AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- TAILWIND -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>Sécurité du compte - Professionnel - PACT</title>
</head>

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
                <a href="pro/compte/securite" class="underline">Sécurité</a>
            </p>

            <hr class="mb-8">

            <p class="text-2xl mb-4">Informations sensibles</p>
            <p class="text-sm">Définissez un nouveau mot de passe fiable, respectant les conditions
                de sécurité minimum suivantes :</p>
            <ul class="mb-3 text-sm">
                <li>- 8 caratères</li>
                <li>- 1 majuscule</li>
                <li>- 1 caractère numérique</li>
            </ul>

            <form action="/pro/compte/securite/" class="flex flex-col" method="post">
                <div class="relative w-full">
                    <label class="text-lg" for="mdp">Mot de passe actuel</label>
                    <input class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 "
                        title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                        type="password" id="mdp" name="mdp" pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$">
                    <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                        id="togglePassword1"></i>
                </div>

                <div class="relative w-full">
                    <label class="text-lg" for="newMdp">Nouveau mot de passe</label>
                    <input class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 "
                        title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                        type="password" id="newMdp" name="newMdp">
                    <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                        id="togglePassword2"></i>
                </div>

                <div class="relative w-full">
                    <label class="text-lg" for="confNewMdp">Confirmation nouveau mot de passe</label>
                    <input class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 "
                        title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                        type="password" id="confNewMdp" name="confNewMdp">
                    <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                        id="togglePassword3"></i>
                </div>

                <span id="success-message" class="success text-green-600 text-sm"></span>
                <span id="error-message" class="error text-rouge-logo text-sm"></span>

                <input type="submit" id="save" value="Modifier mon mot de passe"
                    class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-sm text-white bg-primary  border border-transparent rounded-full"
                    disabled>
            </form>

            <hr class="my-4">

            <!-- PARTIE SUR LE TOTP -->
            <div id="div-for-totp" class="flex flex-col items-start gap-4 self-stretch">
                <p class="text-2xl mb-4">Option TOTP</p>
                <a onclick="document.getElementById('pop-up-info-totp').classList.remove('hidden')"
                    class="italic underline cursor-pointer">Késako ?</a>

                <div id="pop-up-info-totp"
                    class="z-30 fixed top-0 left-0 h-full w-full flex hidden items-center justify-center">
                    <!-- Background blur -->
                    <div class="fixed top-0 left-0 w-full h-full bg-blur/25 backdrop-blur"
                        onclick="document.getElementById('pop-up-info-totp').classList.add('hidden');">
                    </div>
                    <!-- La pop-up (vue)-->
                    <?php
                    require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/pop_up_info_totp.php';
                    ?>
                </div>

                <!-- POUVOIR ACTIVER LE TOTP -->
                <?php if ($pro['totp_active'] == false) { print_r($pro)?>

                    <div class="flex gap-2 items-center self-start">
                        <a id="load-totp-btn"
                            onclick="if(confirm('Vous allez voir un code secret ainsi q\'un QR code à scanner avec une application dédiée sur votre téléphone. Une fois l\'option activée, vous ne pourrez pas récupérer ce code. Nous vous recommandons de le noter à un endroit sûr.')) loadTOTP();"
                            class="max-w-sm px-4 py-2 text-sm hover:text-primary hover:border hover:border-primary hover:bg-transparent text-white bg-primary rounded-full cursor-pointer">Activer
                            l'option TOTP</a>
                        <!-- Symbole de chargement -->
                        <img id="loading-indicator" class="w-8 h-6" style="display: none;" src="/public/images/loading.gif"
                            alt="Chargement...">
                    </div>

                    <!-- CONTENIR LES INFOS CLÉS DU TOTP -->
                    <div id="totp-container" class="break-words max-w-full"></div>

                    <!-- ÉCRIRE LE CODE SECRET POUR CONFIRMER L'ACTIVATION -->
                    <div id="confirm-totp-div" class="flex flex-col items-stretch gap-2 hidden">
                        <label for="confirmTOTP">Pour confirmer l'activation de l'option TOTP, veuillez resaisir le code
                            secret donné ci-dessus en gras :</label>
                        <input class="border border-black p-1" type="text" name="confirmTOTP" id="confirmTOTP">
                        <a id="confirm-totp-btn" onclick="
                            if (document.getElementById('confirmTOTP').value == document.getElementById('secret-span').innerHTML) {
                                if(confirm('Une fois l\'option activée, vous devrez désormais utiliser votre TOTP pour vous connecter.'))
                                {
                                    confirmTOTP();
                                }
                            } else {
                                alert('Le code secret saisi n\'est pas le bon');
                            }"
                            class="self-start max-w-sm px-4 py-2 text-sm hover:text-primary hover:border hover:border-primary hover:bg-transparent text-white bg-primary rounded-full cursor-pointer">Confirmer</a>
                        <!-- Symbole de chargement -->
                        <img id="loading-indicator-confirm" class="w-8 h-6" style="display: none;"
                            src="/public/images/loading.gif" alt="Chargement...">
                    </div>

                    <script>
                        // Initialiser l'OTP et transmettre (une fois) les codes secrets
                        function loadTOTP() {
                            // Afficher le loader pendant le chargement
                            $('#loading-indicator').show();

                            // Désactiver le bouton pendant le chargement
                            $('#load-totp-btn').prop('disabled', true);

                            $.ajax({
                                url: '/scripts/get_totp.php',
                                type: 'GET',
                                data: {},

                                // Si on a une réponse
                                success: function (response) {
                                    if (response) {
                                        data = JSON.parse(response);
                                        try {
                                            $('#totp-container').append("<div class='max-w-[600px]'><p class='w-full'>Votre code secret TOTP : <span class=font-bold id='secret-span'>" + data.secret + "</span></p><div>");
                                            $('#totp-container').append("<br>");
                                            $('#totp-container').append("<p>Scannez ce QR code avec votre application d'authentification OTP : </p><img src=" + data.qr_code_uri + ">");
                                            document.getElementById('confirm-totp-div').classList.remove('hidden');
                                        } catch (e) {
                                            console.log(e);
                                        }

                                        $('#load-totp-btn').prop('disabled', true).text('');
                                        document.getElementById('load-totp-btn').classList.add('hidden');
                                    } else {
                                        $('#totp-container').append('Erreur lors de la réception des données TOTP');
                                    }
                                },

                                // A la fin de la requête
                                complete: function () {
                                    // Masquer le loader après la requête
                                    $('#loading-indicator').hide();
                                    // Réactiver le bouton après la requête (que ce soit réussi ou non)
                                    $('#load-totp-btn').prop('disabled', false);
                                }
                            });
                        }

                        // Confirmer l'OTP en BDD
                        function confirmTOTP() {

                            // Afficher le loader pendant le chargement
                            $('#loading-indicator-confirm').show();

                            // Désactiver le bouton pendant le chargement
                            $('#confirm-totp-btn').prop('disabled', true);

                            $.ajax({
                                url: '/scripts/confirm_totp.php',
                                type: 'GET',
                                data: {
                                    secret: document.getElementById('confirmTOTP').value
                                },

                                // Si on a une réponse
                                success: function (response) {
                                    data = JSON.parse(response);
                                    try {
                                        $('#div-for-totp').append("<p class='text-green-400'>" + data.message + "</p>");
                                        document.getElementById('totp-container').classList.add('hidden');
                                        document.getElementById('confirm-totp-div').classList.add('hidden');
                                    } catch (e) {
                                        console.log(e);
                                    }

                                    $('#confirm-totp-btn').prop('disabled', true).text('');
                                    document.getElementById('confirm-totp-btn').classList.add('hidden');
                                },

                                error: function (xhr, status, error) {
                                    console.log('Erreur : ' + error);
                                    console.log('Statut : ' + status);
                                    console.log('Réponse : ' + xhr.responseText);
                                },

                                // A la fin de la requête
                                complete: function () {
                                    // Masquer le loader après la requête
                                    $('#loading-indicator').hide();
                                    // Réactiver le bouton après la requête (que ce soit réussi ou non)
                                    $('#load-totp-btn').prop('disabled', false);
                                }
                            });
                        }
                    </script>
                <?php } else { ?>
                    <p class="text-green-400">Votre option TOTP est activée.</p>
                <?php } ?>

            </div>

            <hr class="my-4">

            <?php
            // Connexion avec la bdd
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
            if ($pro['data']['type'] == 'prive') {
                $stmt = $dbh->prepare("SELECT api_key FROM sae_db._pro_prive WHERE id_compte = ?");
            } else {
                $stmt = $dbh->prepare("SELECT api_key FROM sae_db._pro_public WHERE id_compte = ?");
            }
            $stmt->bindParam(1, $pro['id_compte']);
            $stmt->execute();
            $key = $stmt->fetch();
            $prefix = 'tchatator';
            $key = substr(strstr($key['api_key'], '_'), 0, 5);
            $key = $prefix . $key . '...';
            ?>

            <div class="flex">
                <p class="text-sm">Clé API Tchatator :</p>
                &nbsp;
                <p id="apiKey" class="text-sm cursor-pointer blur-sm hover:blur-none" onclick="copyToClipboard()">
                    <?php echo $key; ?>
                </p>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>

    <script>
        const mdp = document.getElementById('mdp');
        const newMdp = document.getElementById('newMdp');
        const confNewMdp = document.getElementById('confNewMdp');
        const togglePassword1 = document.getElementById('togglePassword1');
        const togglePassword2 = document.getElementById('togglePassword2');
        const togglePassword3 = document.getElementById('togglePassword3');

        if (togglePassword1) {
            togglePassword1.addEventListener('click', function () {
                if (mdp.type === 'password') {
                    mdp.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    mdp.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }

        if (togglePassword2) {
            togglePassword2.addEventListener('click', function () {
                if (newMdp.type === 'password') {
                    newMdp.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    newMdp.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }

        if (togglePassword3) {
            togglePassword3.addEventListener('click', function () {
                if (confNewMdp.type === 'password') {
                    confNewMdp.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    confNewMdp.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }

        const save = document.getElementById('save');
        const errorMessage = document.getElementById('error-message');

        function activeSave() {
            if (mdp.value.match(mdp.pattern) && newMdp.value.match(mdp.pattern) && confNewMdp.value.match(mdp.pattern)) {
                if (mdp.value !== newMdp.value) {
                    if (newMdp.value === confNewMdp.value) {
                        save.disabled = false;
                        save.classList.remove("opacity-50");
                        save.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
                        errorMessage.textContent = "";
                    } else {
                        errorMessage.textContent = "Les nouveaux mots de passe ne correspondent pas.";
                        save.disabled = true;
                        save.classList.add("opacity-50");
                        save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
                    }
                } else {
                    errorMessage.textContent = "Le nouveau mot de passe doit être différent de l'ancien.";
                    save.disabled = true;
                    save.classList.add("opacity-50");
                    save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
                }
            } else {
                if (!confNewMdp.value.match(mdp.pattern)) {
                    errorMessage.textContent = "La confirmation du nouveau mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
                }
                if (!newMdp.value.match(mdp.pattern)) {
                    errorMessage.textContent = "Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
                }
                if (!mdp.value.match(mdp.pattern)) {
                    errorMessage.textContent = "Le mot de passe actuel doit contenir au moins 8 caractères, une majuscule et un chiffre.";
                }
                save.disabled = true;
                save.classList.add("opacity-50");
                save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        mdp.addEventListener('input', activeSave);
        newMdp.addEventListener('input', activeSave);
        confNewMdp.addEventListener('input', activeSave);

        function copyToClipboard() {
            // Sélectionner le contenu du paragraphe
            const content = document.getElementById('apiKey').textContent;

            // Copier le contenu dans le presse-papiers
            navigator.clipboard.writeText(content).then(() => {
                alert("Clé API copiée dans le presse-papiers !");
            }).catch(err => {
                console.error('Erreur lors de la copie : ', err);
            });
        }
    </script>

</body>

</html>