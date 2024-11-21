<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/output.css">
    <title>Profil</title>
    <script type="module" src="/scripts/main.js" defer></script>
    <script type="module" src="/scripts/loadComponentsPro.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
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
                <a href="/pro/compte/profil" class="underline">Profil</a>
            </p>
        </div>
    </header>
    <div id="menu-pro"></div>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="max-w-[44rem] m-auto flex flex-col">
            <p class="text-h1 mb-4">Informations publiques</p>

            <label class="text-h3" for="nom">Dénomination/Nom de l'organisation</label>
            <input value="Dénomination/Nom de l'organisation" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="nom" name="nom" title="" value="<?php echo $pseudo; ?>" maxlength="255">

            <label class="text-h3" for="adresse">Adresse</label>
            <input value="Adresse" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="adresse" name="adresse" title="" value="<?php echo $adresse; ?>" maxlength="255"">

            <button id="save" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
                Enregistrer les modifications
            </button>

            <hr class="mb-8">

            <div class="max-w-[23rem] mx-auto">
                <a href="/pro/compte/profil" class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                    <i class="w-[50px] text-center text-5xl fa-solid fa-user"></i>
                    <div class="w-full">
                        <p class="text-h2">Avis</p>
                        <p class="text-small">Consulter l’ensemble des avis sur mes offres de la PACT.</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
    <div id="footer-pro"></div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const initialValues = {
            nom: document.getElementById("nom").value,
            adresse: document.getElementById("adresse").value,
        };

        function activeSave() {
            const save1 = document.getElementById("save1");
            const nom = document.getElementById("nom").value;
            const adresse = document.getElementById("adresse").value;

            if (nom !== initialValues.nom || adresse !== initialValues.adresse) {
                save.disabled = false;
                save.classList.remove("opacity-50");
                save.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save.disabled = true;
                save.classList.add("opacity-50");
                save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("nom").addEventListener("input", activeSave);
        document.getElementById("adresse").addEventListener("input", activeSave);
    });
</script>