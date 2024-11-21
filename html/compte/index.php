<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/output.css">
    <title>Mon compte</title>
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
            <p class="text-h1">Prénom Nom</p>
        </div>
    </header>
    <main class="md:w-full mt-0 m-auto flex flex-col max-w-[1280px] p-2">
        <div id=menu class="absolute md:block"></div>
        <div class="mt-8 flex flex-col items-center">
            <a href="/compte/profil" class="cursor-pointer rounded-lg shadow-2xl space-x-8 flex items-center px-8 py-4">
                <i class="text-5xl fa-solid fa-user"></i>
                <div>
                    <p class="text-h2">Profil</p>
                    <p>Modifier mon profile public.</p>
                    <p>Voir mes activités récentes.</p>
                </div>
            </a>
            <a href="/compte/paramètres" class="cursor-pointer rounded-lg shadow-2xl space-x-8 flex items-center px-8 py-4">
                <i class="text-5xl fa-solid fa-gears"></i>
                <div>
                    <p class="text-h2">Paramètres</p>
                    <p>Modifier mes informations privées.</p>
                    <p>Supprimer mon compte.</p>
                </div>
            </a>
        </div>
    </main>
    <div id="footer"></div>
</body>

</html>