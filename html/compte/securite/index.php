<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Vérifier que l'on est bien connecté
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$membre = verifyMember();

if (isset($_POST['mdp'])) {
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
    $controllerMembre = new MembreController();
    $currentPassword = $controllerMembre->getMdpMembre($membre['id_compte']);

    if (password_verify($_POST['mdp'], $currentPassword)) {
        $_SESSION['message_pour_notification'] = 'Informations mises à jour';
        $mdp = password_hash($_POST['newMdp'], PASSWORD_DEFAULT);
        $controllerMembre->updateMembre($membre['id_compte'], false, $mdp, false, false, false, false);
    }

    unset($_POST['mdp']);
    unset($_POST['newMdp']);
    unset($_POST['newConfMdp']);
    $membre = verifyMember();
}
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

    <!-- AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Sécurité du compte - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <main class="w-full flex justify-center grow m-auto p-6">
        <div class="max-w-[1280px] w-full p-2 flex justify-center">
            <div id="menu">
                <?php
                require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/menu.php';
                ?>
            </div>

            <div class="flex flex-col md:mx-10 grow">
                <p class="text-xl p-4">
                    <a href="/compte">Mon compte</a>
                    >
                    <a href="/compte/securite/" class="underline">Sécurité</a>
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

                <form action="/compte/securite/" class="flex flex-col gap-4" method="post">
                    <div class="relative w-full">
                        <label class="text-lg" for="mdp">Mot de passe actuel</label>
                        <input class="border border-secondary text-sm  p-2 bg-white w-full h-12"
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            type="password" id="mdp" name="mdp" pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$">
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>

                    <div class="relative w-full">
                        <label class="text-lg" for="newMdp">Nouveau mot de passe</label>
                        <input class="border border-secondary text-sm p-2 bg-white w-full h-12"
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            type="password" id="newMdp" name="newMdp">
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>

                    <div class="relative w-full">
                        <label class="text-lg" for="confNewMdp">Confirmation nouveau mot de passe</label>
                        <input class="border border-secondary text-sm p-2 bg-white w-full h-12"
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            type="password" id="confNewMdp" name="confNewMdp">
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>

                    <input type="submit" id="saveBtn" value="Modifier mon mot de passe"
                        class="self-end opacity-50 max-w-sm px-4 py-2 mb-4 text-sm text-white bg-primary cursor-pointer border border-transparent rounded-full"
                        disabled>
                </form>

                <hr class="mb-8">

                <!-- PARTIE SUR LE TOTP -->
                <div id="div-for-totp" class="flex flex-col items-start gap-2 mb-4">
                    <p class="text-2xl mb-2">Option TOTP</p>

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
                    <?php if ($membre['totp_active'] == false) { ?>

                        <div class="flex items-center self-start">
                            <a id="load-totp-btn"
                                onclick="if(confirm('Vous allez voir un code secret ainsi q\'un QR code à scanner avec une application dédiée sur votre téléphone. Une fois l\'option activée, vous ne pourrez pas récupérer ce code. Nous vous recommandons de le noter à un endroit sûr.')) loadTOTP();"
                                class="max-w-sm px-4 py-2 text-sm border border-primary bg-primary hover:text-primary hover:bg-white text-white rounded-full cursor-pointer">Activer
                                l'option TOTP</a>
                            <!-- Symbole de chargement -->
                            <img id="loading-indicator" class="w-8 h-6" style="display: none;"
                                src="/public/images/loading.gif" alt="Chargement...">
                        </div>

                        <!-- CONTENIR LES INFOS CLÉS DU TOTP -->
                        <div id="totp-container" class="break-words max-w-full"></div>

                        <!-- ÉCRIRE LE CODE SECRET POUR CONFIRMER L'ACTIVATION -->
                        <div id="confirm-totp-div" class="flex flex-col gap-2 items-stretch hidden">
                            <label for="confirmTOTP">Pour confirmer l'activation de l'option TOTP, veuillez ressaisir le
                                code secret donné ci-dessus en gras :</label>
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
                                class="self-start px-4 py-2 text-sm hover:text-primary border border-primary hover:bg-transparent text-white bg-primary rounded-full cursor-pointer">Confirmer</a>
                            <!-- Symbole de chargement -->
                            <img id="loading-indicator-confirm" class="w-8 h-6" style="display: none;"
                                src="/public/images/loading.gif" alt="Chargement...">
                        </div>
                    <?php } else { ?>
                        <p class="text-green-400">Votre option TOTP est activée.</p>
                    <?php } ?>

                    <a onclick="document.getElementById('pop-up-info-totp').classList.remove('hidden')"
                        class="underline cursor-pointer text-sm">Késako ?</a>

                </div>

                <hr class="mb-8">

                <!-- PARTIE SUR LA CLÉ API -->
                <div class="flex mb-4">
                    <p class="text-sm">Clé API Tchatator :</p>
                    &nbsp;
                    <p id="apiKey" class="text-sm cursor-pointer blur-sm hover:blur-none"
                        onclick="copyToClipboard(this)">
                        <?php echo $membre['api_key']; ?>
                    </p>
                </div>

                <hr class="mb-8">

                <!-- SUPPRIMER SON COMPTE -->
                <a onclick="document.getElementById('pop-up-suppression-compte').classList.remove('hidden')"
                    class="cursor-pointer underline max-w-[23rem] w-full text-sm">
                    Supprimer mon compte
                </a>

                <div id="pop-up-suppression-compte"
                    class="z-30 fixed top-0 left-0 h-full w-full flex hidden items-center justify-center">
                    <!-- Background blur -->
                    <div class="fixed top-0 left-0 w-full h-full bg-blur/25 backdrop-blur"
                        onclick="document.getElementById('pop-up-suppression-compte').classList.add('hidden');">
                    </div>
                    <!-- La pop-up (vue)-->
                    <?php
                    require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/pop_up_suppression_compte.php';
                    ?>
                </div>
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
        const saveBtn = document.getElementById('saveBtn');

        function triggerSaveBtn() {
            if (mdp.value.match(mdp.pattern) && newMdp.value.match(mdp.pattern) && confNewMdp.value.match(mdp.pattern)) {
                if (mdp.value !== newMdp.value) {
                    if (newMdp.value === confNewMdp.value) {
                        saveBtn.disabled = false;
                        saveBtn.classList.remove("opacity-50");
                    } else {
                        saveBtn.disabled = true;
                        saveBtn.classList.add("opacity-50");
                    }
                } else {
                    saveBtn.disabled = true;
                    saveBtn.classList.add("opacity-50");
                }
            } else {
                saveBtn.disabled = true;
                saveBtn.classList.add("opacity-50");
            }
        }

        mdp.addEventListener('input', triggerSaveBtn);
        newMdp.addEventListener('input', triggerSaveBtn);
        confNewMdp.addEventListener('input', triggerSaveBtn);
    </script>

</body>

</html>