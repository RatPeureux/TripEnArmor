<!-- 
    Composant du header pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='header'></div> dans votre code html
    (responsive)
-->
<header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
    <div class="flex w-full justify-between items-center">
        <a onclick="toggleMenu()" class="flex gap-4 md:hidden">
            <i class="text-3xl fa-solid fa-bars"></i>
            <img src="/public/images/logo.svg" alt="Logo" width="44">
        </a>
        <a href="/" class="flex gap-3 items-center">
            <img class="hidden md:block" src="/public/images/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase text-PACT hidden md:block">PACT</h1>
        </a>
        <div class="flex gap-10 items-center">
            <div class="relative flex items-center">
                <div class="relative flex items-center" id="open-search">
                    <input type="text" placeholder="Rechercher..." class="border border-primary p-2 rounded-full pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200" aria-label="Recherche">
                    <div class="absolute bg-white w-12 right-4 flex items-center justify-center">
                        <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                    </div>
                </div>
            </div>
            <!-- Si connecté -->
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
            if (isConnectedAsMember()) { ?>
                <div class="flex items-center gap-4">
                    <a href="/compte">
                        <i class="text-3xl fa-regular fa-user"></i>
                    </a>
                    <a href="/scripts/logout.php" class="flex flex-col items-center" onclick="return confirmLogout()">
                        <div class="border border-primary rounded-lg p-2">
                            <p>
                                Se déconnecter
                            </p>
                        </div>
                    </a>
                </div>
            <?php } else { ?>
                <!-- Sinon si pas connecté -->
                <a href="/connexion">
                    <!-- <i class="text-3xl fa-regular fa-user"></i> -->
                    <div class="border border-primary rounded-lg p-2">
                        <p>
                            Se connecter
                        </p>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</header>