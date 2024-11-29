<!-- 
    Composant du header pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='header'></div> dans votre code html
-->
<!-- VERSION TABLETTE ET + -->
<header class="hidden md:block z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
    <div class="flex w-full justify-between items-center">
        <a href="/" class="flex gap-3 items-center">
            <img class="hidden md:block" src="/public/images/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase text-PACT">PACT</h1>
        </a>
        <div class="flex gap-10 items-center">
            <div class="relative flex items-center" id="open-search">
                <input type="text" placeholder="Rechercher..." class="border border-primary p-2 rounded-full pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200" aria-label="Recherche">
                <div class="absolute w-12 right-4 flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass fa-lg"></i>
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

<!-- VERSION TÉLÉPHONE -->
<header class="block md:hidden z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
    <div class="flex w-full justify-start items-center gap-4">
        <a onclick="toggleMenu()">
            <i class="text-3xl fa-solid fa-bars"></i>
        </a>
        <a href="/">
            <img src="/public/images/logo.svg" alt="Logo" width="50">
        </a>
        <div class="w-full relative flex items-center" id="open-search">
            <input type="text" placeholder="Rechercher..." class="w-full border border-primary p-2 rounded-full pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200" aria-label="Recherche">
            <div class="absolute right-4 flex items-center justify-center">
                <i class="fa-solid fa-magnifying-glass fa-lg"></i>
            </div>
        </div>
        <!-- Si connecté -->
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
        if (isConnectedAsMember()) { ?>
            <a href="/compte">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
        <?php } else { ?>
            <!-- Sinon si pas connecté -->
            <a href="/connexion">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
        <?php } ?>
    </div>
</header>