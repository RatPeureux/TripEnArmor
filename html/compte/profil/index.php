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
                <i onclick="history.back()" class="mr-4 fa-solid fa-arrow-left fa-xl cursor-pointer"></i>
                <a href="/compte">Mon compte</a>
                >
                <a href="/compte/profil">Profil</a>
            </p>
        </div>
    </header>
    <main class="md:w-full mt-0 m-auto flex flex-col max-w-[1280px] p-2">
        <p class="text-h1">Informations publiques</p>

        <label class="text-h3" for="id">Nom d'utilisateur</label>
        <input value="Nom d'utilisateur" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="id" name="id" title="pseudo / mail / téléphone" value="<?php echo $id; ?>" maxlength="255">

        <button id="save" class="self-end opacity-50 max-w-sm h-12 my-1.5 px-4 text-white font-bold bg-primary rounded-lg border border-transparent" disabled>
            Enregistrer les modifications
        </button>

        <br><hr><br>

        <a href="/compte/paramètres" class="cursor-pointer rounded-lg shadow-2xl space-x-8 flex items-center px-8 py-4">
            <i class="text-5xl fa-solid fa-egg"></i>
            <div>
                <p class="text-h2">Avis</p>
                <p>Consulter l’ensemble des avis que j’ai postés sur les différentes offres de la PACT.</p>
            </div>
        </a>
    </main>
    <div id="footer"></div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputField = document.getElementById("id");
        const save = document.getElementById("save");

        // Valeur initiale du champ
        let initialValue = inputField.value;

        // Écouter les modifications
        inputField.addEventListener("input", function () {
            // Comparer la valeur actuelle avec la valeur initiale
            if (inputField.value !== initialValue) {
                save.disabled = false;
                focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white
                save.classList.add("cursor-pointer");
                save.classList.add("cursor-pointer");
                save.classList.add("cursor-pointer");
                save.classList.add("cursor-pointer");
                save.classList.add("cursor-pointer");
                save.classList.remove("opacity-50");
            } else {
                save.disabled = true;
                save.classList.add("opacity-50");
            }
        });
    });
</script>
