<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/output.css">
    <title>Paramètres</title>
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
        <p class="text-h1">Informations privées</p>
        
    </main>
    <div id="footer"></div>
</body>

</html>