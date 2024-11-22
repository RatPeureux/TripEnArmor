<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Page non trouvée</title>
    <script type="module" src="/scripts/loadComponentsPro.js" defer=""></script>
    <script type="module" src="/scripts/main.js" defer=""></script>
</head>

<body class="min-h-screen flex flex-col">
    <div id="header-pro"></div>
    <div id="menu-pro"></div>
    <main class="w-full mt-20 m-auto max-w-[1280px] p-2">
        <div class="text-center">
            <h1 class="font-cormorant text-[10rem]">404</h1>
            <p>Ce n'est pas la page que vous recherchez.</p>
            <img src="https://i.pinimg.com/originals/e0/5a/70/e05a70b23f36987ff395063a1e193db7.gif" class="mt-10 m-auto rounded-lg" alt="tottereau" width="250">
        </div>
    </main>
    <div id="footer-pro" class=""></div>
</body>

</html>