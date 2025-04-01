<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Récupérer les infos du pro
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
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
        $_SESSION['message_pour_notification'] = 'Informations mises à jour';
        if ($pro['data']['type'] == 'prive') {
            $controllerProPrive->updateProPrive($pro['id_compte'], false, $mdp, false, false, false, false);
        } else {
            $controllerProPublic->updateProPublic($pro['id_compte'], false, $mdp, false, false, false, false);
        }
    }

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

    <!-- NOS FICHIERS -->
    <script type="module" src="/scripts/main.js"></script>
    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">

    <!-- AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Sécurité du compte - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <main class="md:w-full mt-0 m-auto p-6">
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
                    <div class="relative w-full">
                        <input class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 "
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            type="password" id="mdp" name="mdp" pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$">
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-1/2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>
                </div>

                <div class="relative w-full">
                    <label class="text-lg" for="newMdp">Nouveau mot de passe</label>
                    <div class="relative w-full">
                        <input class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 "
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            type="password" id="newMdp" name="newMdp">
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-1/2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>
                </div>

                <div class="relative w-full">
                    <label class="text-lg" for="confNewMdp">Confirmation nouveau mot de passe</label>
                    <div class="relative w-full">
                        <input class="border text-sm border-secondary p-2 bg-white w-full h-12 mb-3 "
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            type="password" id="confNewMdp" name="confNewMdp">
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-1/2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>
                </div>

                <input type="submit" id="save" value="Modifier mon mot de passe"
                    class="self-end opacity-50 max-w-sm mb-4 px-4 py-2 text-sm text-white bg-primary border border-transparent rounded-full"
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
                <?php if ($pro['totp_active'] == false) { ?>

                    <div class="flex items-center self-start">
                        <a id="load-totp-btn"
                            onclick="if(confirm('Vous allez voir un code secret ainsi q\'un QR code à scanner avec une application dédiée sur votre téléphone. Une fois l\'option activée, vous ne pourrez pas récupérer ce code. Nous vous recommandons de le noter à un endroit sûr.')) loadTOTP();"
                            class="max-w-sm px-4 py-2 text-sm hover:text-primary border border-primary hover:bg-transparent text-white bg-primary rounded-full cursor-pointer">Activer
                            l'option TOTP</a>
                        <!-- Symbole de chargement -->
                        <img id="loading-indicator" class="w-8 h-6" style="display: none;" src="/public/images/loading.gif"
                            alt="Chargement...">
                    </div>

                    <!-- CONTENIR LES INFOS CLÉS DU TOTP -->
                    <div id="totp-container" class="break-words max-w-full"></div>

                    <!-- ÉCRIRE LE CODE SECRET POUR CONFIRMER L'ACTIVATION -->
                    <div id="confirm-totp-div" class="flex flex-col gap-2 items-stretch hidden">
                        <label for="confirmTOTP">Pour confirmer l'activation de l'option TOTP, veuillez ressaisir le code
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

            <!-- <hr class="mb-8">

            <div class="flex">
                <p class="text-sm">Clé API Tchatator :</p>
                &nbsp;
                <p id="apiKey" class="text-sm cursor-pointer blur-sm hover:blur-none" onclick="copyToClipboard(this)">
                    <?php echo $pro['api_key']; ?>
                </p>
            </div> -->
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
        const save = document.getElementById('save');

        function triggerSaveBtn() {
            if (mdp.value.match(mdp.pattern) && newMdp.value.match(mdp.pattern) && confNewMdp.value.match(mdp.pattern)) {
                if (mdp.value !== newMdp.value) {
                    if (newMdp.value === confNewMdp.value) {
                        save.disabled = false;
                        save.classList.remove("opacity-50");
                        save.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
                    } else {
                        save.disabled = true;
                        save.classList.add("opacity-50");
                        save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
                    }
                } else {
                    save.disabled = true;
                    save.classList.add("opacity-50");
                    save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
                }
            } else {
                if (!confNewMdp.value.match(mdp.pattern)) {
                }
                if (!newMdp.value.match(mdp.pattern)) {
                }
                if (!mdp.value.match(mdp.pattern)) {
                }
                save.disabled = true;
                save.classList.add("opacity-50");
                save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        mdp.addEventListener('input', triggerSaveBtn);
        newMdp.addEventListener('input', triggerSaveBtn);
        confNewMdp.addEventListener('input', triggerSaveBtn);
    </script>

</body>

</html>