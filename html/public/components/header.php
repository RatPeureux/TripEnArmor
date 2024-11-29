<!-- 
    Composant du header pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='header'></div> dans votre code html
    (responsive)
-->
<header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
    <div class="flex w-full justify-between items-center">
        <a onclick="toggleMenu()" class="md:hidden">
            <i class="text-3xl fa-solid fa-bars"></i>
        </a>
        <a href="/" class="flex gap-3 items-center">
            <img src="/public/images/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase text-PACT">PACT</h1>
        </a>
        <div class="flex gap-4 items-center">
            <!-- Si connecté -->
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
            if (isConnectedAsMember()) { ?>
                <a href="/scripts/logout.php" class="flex flex-col items-center" onclick="return confirmLogout()">
                    <i class="text-3xl fa-regular fa-user"></i>
                    <p class="italic">(Auth.)</p>
                </a>
            <?php } else { ?>
                <!-- Sinon si pas connecté -->
                <a href="/connexion">
                    <i class="text-3xl fa-regular fa-user"></i>
                </a>
            <?php } ?>
        </div>
    </div>
</header>