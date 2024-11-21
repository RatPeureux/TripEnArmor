<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/output.css">
    <title>Paramètres</title>
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
                <a href="/pro/compte/profil" class="underline">Paramètres</a>
            </p>
        </div>
    </header>
    <div id="menu-pro"></div>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <div class="max-w-[44rem] m-auto flex flex-col">
            <p class="text-h1 mb-4">Informations privées</p>

            <label class="text-h3" for="email">E-mail</label>
            <input value="E-mail" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="email" name="email" title="" value="<?php echo $email; ?>" maxlength="255">

            <label class="text-h3" for="num_tel">Téléphone</label>
            <input value="Téléphone" class="border-2 border-secondary p-2 bg-white w-36 h-12 mb-3 rounded-lg" type="text" id="num_tel" name="num_tel" title="" value="<?php echo $num_tel; ?>" maxlength="255"">

            <button id="save1" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
            Enregistrer les modifications
            </button>

            <hr class="mb-8">

            <label class=" text-h3" for="iban">IBAN</label>
            <input value="IBAN" class="border-2 border-secondary p-2 bg-white w-full h-12 mb-3 rounded-lg" type="text" id="iban" name="iban" title="" value="<?php echo $iban; ?>" maxlength="255">

            <button id="save2" class="self-end opacity-50 max-w-sm h-12 mb-8 px-4 font-bold text-small text-white bg-primary rounded-lg border border-transparent" disabled>
            Enregistrer les modifications
            </button>
        </div>
    </main>
    <div id="footer-pro"></div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const initialValues = {
            email: document.getElementById("email").value,
            num_tel: document.getElementById("num_tel").value,
            iban: document.getElementById("iban").value,
        };

        function activeSave1() {
            const save1 = document.getElementById("save1");
            const email = document.getElementById("email").value;
            const num_tel = document.getElementById("num_tel").value;

            if (email !== initialValues.email || num_tel !== initialValues.num_tel) {
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
            const iban = document.getElementById("iban").value;

            if (iban !== initialValues.iban) {
                save2.disabled = false;
                save2.classList.remove("opacity-50");
                save2.classList.add("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            } else {
                save2.disabled = true;
                save2.classList.add("opacity-50");
                save2.classList.remove("cursor-pointer", "hover:text-white", "hover:border-orange-600", "hover:bg-orange-600", "focus:scale-[0.97]");
            }
        }

        document.getElementById("email").addEventListener("input", activeSave1);
        document.getElementById("num_tel").addEventListener("input", activeSave1);
        document.getElementById("iban").addEventListener("input", activeSave2);
    });
</script>