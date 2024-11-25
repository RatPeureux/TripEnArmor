<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <title>Paramètres</title>
    <script type="module" src="/scripts/main.js" defer></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    <title>Paramètres</title>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <div class="flex w-full items-center">
            <a href="" onclick="toggleMenu()" class="mr-4 md:hidden">
                <i class="text-3xl fa-solid fa-bars"></i>
            </a>
            <p class="text-h2">
                <a href="/compte">Mon compte</a>
                >
                <p class="underline">Paramètres</p>
            </p>
        </div>
    </header>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div id="menu" class="absolute md:block"></div>
        <div class="max-w-[44rem] m-auto flex flex-col">
            <p class="text-h1 mb-4">Informations privées</p>

            <div class="flex flex-nowrap space-x-3 mb-1.5">
                <div class="w-full">
                    <label class="text-h3" for="prenom">Prénom</label>
                    <input value="Prénom" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="prenom" name="prenom" maxlength="50">
                </div>
                <div class="w-full">
                    <label class="text-h3" for="nom">Nom</label>
                    <input value="Nom" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="nom" name="nom" maxlength="50">
                </div>
            </div>

            <button id="save1" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
                Enregistrer les modifications
            </button>

            <hr class="mb-8">

            <label class="text-h3" for="email">Adresse mail</label>
            <input value="Adresse mail" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="email" id="email" name="email" maxlength="255">

            <label class="text-h3" for="num_tel">Numéro de téléphone</label>
            <input value="Téléphone" class="border-2 border-secondary p-2 bg-white max-w-36 h-12 mb-3 rounded-lg" type="tel" id="num_tel" name="num_tel" minlength="14" maxlength="14">

            <button id="save2" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
                Enregistrer les modifications
            </button>

            <hr class="mb-8">

            <label class="text-h3" for="adresse">Adresse postale</label>
            <input value="Adresse postale" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="adresse" name="adresse" value="<?php echo $adresse; ?>" maxlength="255"">

            <label class=" text-h3" for="complement">Complément adresse postale</label>
            <input value="Complément adresse postale" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="complement" name="complement" value="<?php echo $adresse; ?>" maxlength="255"">

            <div class=" flex flex-nowrap space-x-3 mb-1.5">
            <div class="w-32">
                <label class="text-h3" for="code">Code postal</label>
                <input value="Code postal" class="border-2 border-secondary p-2 text-right bg-white max-w-32 h-12 mb-3 rounded-lg" type="text" id="code" name="code" value="<?php echo $prenom; ?>" minlength="5" maxlength="5">
            </div>
            <div class="w-full">
                <label class="text-h3" for="ville">Ville</label>
                <input value="Ville" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="ville" name="ville" value="<?php echo $nom; ?>" maxlength="50">
            </div>
        </div>

        <button id="save3" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
            Enregistrer les modifications
        </button>
        </div>
    </main>
    <div id="footer"></div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
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