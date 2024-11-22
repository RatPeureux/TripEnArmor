<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/output.css">
    <title>Profil</title>
    <script type="module" src="/scripts/main.js" defer></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
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
                <p class="underline">Profil</p>
            </p>
        </div>
    </header>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div id="menu" class="absolute md:block"></div>
        <div class="max-w-[44rem] m-auto flex flex-col">
            <p class="text-h1 mb-4">Informations publiques</p>

            <label class="text-h3" for="pseudo">Nom d'utilisateur</label>
            <input value="Nom d'utilisateur" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="pseudo" name="pseudo" maxlength="255">

            <button id="save" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
                Enregistrer les modifications
            </button>

            <hr class="mb-8">

            <div class="max-w-[23rem] mx-auto">
                <a href="/compte/profil" class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                    <i class="w-[50px] text-center text-5xl fa-solid fa-egg"></i>
                    <div class="w-full">
                        <p class="text-h2">Avis</p>
                        <p class="text-small">Consulter l’ensemble des avis que j’ai postés sur les différentes offres de la PACT.</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
    <div id="footer"></div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const initialValues = {
            pseudo: document.getElementById("pseudo").value,
        };

        function activeSave() {
            const save = document.getElementById("save");
            const pseudo = document.getElementById("pseudo").value;

            if (pseudo !== initialValues.pseudo) {
                save.disabled = false;
                save.classList.remove("opacity-50");
                save.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save.disabled = true;
                save.classList.add("opacity-50");
                save.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("pseudo").addEventListener("input", activeSave);
    });
</script>