<!-- 
    Composant du header pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='header'></div> dans votre code html
-->
<header class="flex items-center z-30 w-full bg-white p-4 h-20 border-b-2 border-black top-0">
    <div class="w-full flex items-center justify-between">
        <!-- Menu Burger pour les petits écrans -->
        <div class="md:hidden">
            <button class="flex items-center gap-4 " onclick="toggleMenu()">
                <i class="text-3xl fa-solid fa-bars"></i>
                <img src="/public/images/logo.svg" alt="Logo" width="40">
            </button>
        </div>

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2">
            <img src="/public/images/logo.svg" alt="Logo" width="50" class="hidden md:block">
            <h1 class="font-cormorant uppercase text-PACT hidden md:block">PACT</h1>
        </a>

        <!-- Barre de recherche -->
        <div class="relative flex-1 max-w-lg mx-4">
            <div class="relative flex items-center">
                <input type="text" id="search-field" placeholder="Rechercher par tags..." class="w-full border border-primary p-2 rounded-full pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200" aria-label="Recherche">
                <div class="absolute right-4 flex items-center justify-center transform -translate-y-1/2">
                    <i class="fa-solid fa-magnifying-glass fa-lg cursor-pointer" id="search-btn"></i>
                </div>
                <!-- Bouton de suppression -->
                <button class="hidden absolute right-2 min-w-max flex items-center justify-center bg-white rounded-lg px-2 py-1" id="clear-tags-btn">
                    <i class="text-xl fa-solid fa-times cursor-pointer"></i>
                </button>
            </div>
            <!-- Dropdown de recherche -->
            <div class="absolute top-full left-0 right-0 bg-white border border-base200 rounded-lg shadow-md mt-2 hidden z-10" id="search-menu"></div>
        </div>

        <!-- Actions Utilisateur -->
        <div class="flex items-center gap-4">
            <?php if (isConnectedAsMember()) { ?>
                <!-- Si connecté -->
                <a href="/compte">
                    <i class="text-3xl fa-regular fa-user"></i>
                </a>
                <a href="/scripts/logout.php" class="hidden md:block flex flex-col items-center" onclick="return confirmLogout()">
                    <div class="border border-primary rounded-lg p-2">
                        <p>Se déconnecter</p>
                    </div>
                </a>
            <?php } else { ?>
                <!-- Si non connecté -->
                <a href="/connexion" class="md:hidden">
                    <i class="text-3xl fa-regular fa-user"></i>
                </a>
                <a href="/connexion" class="hidden md:block">
                    <div class="border border-primary rounded-lg p-2">
                        <p>Se connecter</p>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</header>