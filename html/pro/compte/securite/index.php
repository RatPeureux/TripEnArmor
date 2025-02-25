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

    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">
    <script type="module" src="/scripts/main.js"></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Sécurité du compte - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/header-pro.php';
    ?>

    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="m-auto flex flex-col">
            <p class="text-h3 p-4">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="pro/compte/securite" class="underline">Sécurité</a>
            </p>

            <hr class="mb-8">

            <p class="text-h2 mb-4">Informations sensibles</p>
            <p class="text-small">Définissez un nouveau mot de passe fiable, respectant les conditions
                de sécurité minimum suivantes :</p>
            <ul class="mb-3 text-small">
                <li>- 8 caratères</li>
                <li>- 1 majuscule</li>
                <li>- 1 caractère numérique</li>
            </ul>

            <form action="" class="flex flex-col" method="post">
                <div class="relative w-full">
                    <label class="text-h4" for="mdp">Mot de passe actuel</label>
                    <input class="border text-small border-secondary p-2 bg-white w-full h-12 mb-3 "
                        title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                        type="password" id="mdp" name="mdp" pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$">
                    <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                        id="togglePassword1"></i>
                </div>

                <div class="relative w-full">
                    <label class="text-h4" for="newMdp">Nouveau mot de passe</label>
                    <input class="border text-small border-secondary p-2 bg-white w-full h-12 mb-3 "
                        title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                        type="password" id="newMdp" name="newMdp">
                    <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                        id="togglePassword2"></i>
                </div>

                <div class="relative w-full">
                    <label class="text-h4" for="confNewMdp">Confirmation nouveau mot de passe</label>
                    <input class="border text-small border-secondary p-2 bg-white w-full h-12 mb-3 "
                        title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                        type="password" id="confNewMdp" name="confNewMdp">
                    <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                        id="togglePassword3"></i>
                </div>

                <span id="success-message" class="success text-green-600 text-small"></span>
                <span id="error-message" class="error text-rouge-logo text-small"></span>

                <input type="submit" id="save" value="Modifier mon mot de passe"
                    class="self-end opacity-50 max-w-sm my-4 px-4 py-2 text-small text-white bg-primary  border border-transparent rounded-full"
                    disabled>
                </input>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/../view/footer-pro.php';
    ?>
</body>

</html>

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
</script>