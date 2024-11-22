<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <script rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    content: [
        "./html/**/*",
    ],
    theme: {
        extend: {
            fontFamily: {
                'cormorant': ['Cormorant-Bold'],
                'sans': ['Poppins'],
            },
            fontSize: {
                'small': ['14px'],
                'h1': ['32px'],
                'h2': ['24px'],
                'h3': ['20px'],
                'h4': ['18px'],
                'PACT': ['35px', {
                    letterSpacing: '0.2em',
                }],
            },
            colors: {
                'rouge-logo': '#EA4335',
                'primary': '#F2771B',
                'secondary': '#0a0035',
                'base100': '#F1F3F4',
                'base200': '#E0E0E0',
                'base300': '#CCCCCC',
                'neutre': '#000',
                'gris': '#828282',
                'bgBlur': "#F1F3F4",
                'veryGris': "#BFBFBF",
            },
            spacing: {
                '1/6': '16%',
            },
            animation: {
                'expand-width': 'expandWidth 1s ease-out forwards',
            },
            keyframes: {
                expandWidth: {
                    '0%': { width: '100%' },
                    '100%': { width: '0%' },
                },
            },
            boxShadow: {
                'custom': '0 0 12px 12px rgba(210, 210, 210, 0.5)',
            }
        },
    },
    plugins: [],
}
</script>
    <title>Mon compte</title>
    <script type="module" src="/scripts/main.js" defer></script>
    <script type="module" src="/scripts/loadComponentsPro.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <a href="#" onclick="toggleMenu()" class="mr-4 flex gap-4 items-center hover:text-primary duration-100">
            <i class="text-3xl fa-solid fa-bars"></i>
        </a>
        <div class="flex w-full items-center">
            <p class="text-h2">Dénomination/Nom de l'organisation</p>
        </div>
    </header>
    <div id="menu-pro"></div>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="max-w-[23rem] my-8 mx-auto space-y-12 flex flex-col items-center">
            <a href="/pro/compte/profil" class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-user"></i>
                <div class="w-full">
                    <p class="text-h2">Profil</p>
                    <p class="text-small">Modifier mon profil public.</p>
                    <p class="text-small">Voir mes activités récentes.</p>
                </div>
            </a>
            <a href="/pro/compte/paramètres" class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-gear"></i>
                <div class="w-full">
                    <p class="text-h2">Paramètres</p>
                    <p class="text-small">Modifier mes informations privées.</p>
                    <p class="text-small">Supprimer mon compte.</p>
                </div>
            </a>
            <a href="/pro/compte/sécurité" class="cursor-pointer w-full rounded-lg shadow-custom space-x-8 flex items-center mb-8 px-8 py-4">
                <i class="w-[50px] text-center text-5xl fa-solid fa-shield"></i>
                <div class="w-full">
                    <p class="text-h2">Sécurité</p>
                    <p class="text-small">Modifier mes informations sensibles.</p>
                    <p class="text-small">Protéger mon compte.</p>
                </div>
            </a>

            <a href="#"
                class="w-full h-12 p-1 font-bold text-small text-center text-wrap text-rouge-logo bg-transparent rounded-lg flex items-center justify-center border border-rouge-logo hover:text-white hover:bg-red-600 hover:border-red-600 focus:scale-[0.97]">
                Se déconnecter
            </a>
        </div>
    </main>
    <div id="footer-pro"></div>
</body>

</html>