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

    <title>Sécurité du compte - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <div class="flex w-full items-center">
            <a href="#" onclick="toggleMenu()" class="mr-4 flex gap-4 items-center hover:text-primary duration-100">
                <i class="text-3xl fa-solid fa-bars"></i>
            </a>
            <p class="text-h2">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="pro/compte/sécurité" class="underline">Sécurité</a>
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
            <p class="text-h1 mb-4">Informations sensibles</p>
            <p class="text-small">Définissez un nouveau mot de passe fiable, respectant les conditions
                de sécurité minimum suivantes :</p>
            <ul class="mb-3 text-small">
                <li>- 8 caratères</li>
                <li>- 1 majuscule</li>
                <li>- 1 caractère numérique</li>
            </ul>

            <div class="relative w-full">
                <label class="text-h3" for="mdp">Mot de passe actuel</label>
                <input class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="password"
                    id="mdp" name="mdp" pattern=".*[A-Z].*.*\d.*|.*\d.*.*[A-Z].*" minlength="8">

                <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                    id="togglePassword1"></i>
            </div>

            <div class="relative w-full">
                <label class="text-h3" for="newMdp">Nouveau mot de passe</label>
                <input class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="password"
                    id="newMdp" name="newMdp">

                <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                    id="togglePassword2"></i>
            </div>

            <div class="relative w-full">
                <label class="text-h3" for="confNewMdp">Confirmation nouveau mot de passe</label>
                <input class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="password"
                    id="confNewMdp" name="confNewMdp">

                <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                    id="togglePassword3"></i>
            </div>

            <span id="error-message" class="error text-rouge-logo text-small"></span>

            <button id="save"
                class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent"
                disabled>
                Modifier mon mot de passe
            </button>
        </div>
    </main>


    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
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
        togglePassword1.addEventListener('mousedown', function () {
            mdp.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        });
        togglePassword1.addEventListener('mouseup', function () {
            mdp.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        });
    }

    if (togglePassword2) {
        togglePassword2.addEventListener('mousedown', function () {
            newMdp.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        });
        togglePassword2.addEventListener('mouseup', function () {
            newMdp.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        });
    }

    if (togglePassword3) {
        togglePassword3.addEventListener('mousedown', function () {
            confNewMdp.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        });
        togglePassword3.addEventListener('mouseup', function () {
            confNewMdp.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        });
    }

    const save = document.getElementById('save');
    const errorMessage = document.getElementById('error-message');

    function checkFields() {
        const isMdpValid = mdp.value.match(mdp.pattern);
        const isNewMdpValid = newMdp.value.match(mdp.pattern);
        const isConfNewMdpValid = confNewMdp.value.match(mdp.pattern);

        if (mdp.value === 'cacacacaC2') {
            if (isMdpValid && isNewMdpValid && isConfNewMdpValid) {
                if (mdp.value !== newMdp.value) {
                    if (newMdp.value === confNewMdp.value) {
                        save.disabled = false;
                        save.classList.remove("opacity-50");
                        save.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
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
                errorMessage.textContent = "Le nouveau mot de passe doit respecter les conditions de sécurité minimum.";
                save.disabled = true;
                save.classList.add("opacity-50");
                save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        } else {
            errorMessage.textContent = "Le mot de passe actuel est incorrect.";
            save.disabled = true;
            save.classList.add("opacity-50");
            save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
        }
    }

    mdp.addEventListener('input', checkFields);
    newMdp.addEventListener('input', checkFields);
    confNewMdp.addEventListener('input', checkFields);
</script>