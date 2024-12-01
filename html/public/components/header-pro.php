<!-- 
    Composant du header pour le pro
    Pour l'ajouter, écrier la balise <div id='header-pro'></div> dans votre code html
-->
<header class="z-30 w-full bg-bgBlur/75 backdrop-blur flex items-center p-4 h-20 border-b-2 border-black fixed top-0">
    <div class="flex w-full items-center relative">
        <!-- Partie gauche -->
        <div class="flex-shrink-0 flex items-center">
            <a href="#" onclick="toggleMenu()" class="flex gap-4 items-center hover:text-primary duration-100">
                <i class="text-3xl fa-solid fa-bars"></i>
                <p class="hidden md:block">Menu</p>
            </a>
        </div>

        <!-- Logo centré -->
        <a href="/" class="absolute left-1/2 transform -translate-x-1/2 flex gap-2 items-center">
            <img src="/public/images/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase whitespace-nowrap text-PACT">
                <span class="hidden md:inline">PACT</span> Pro
            </h1>
        </a>

        <!-- Partie droite -->
        <div class="flex-shrink-0 flex items-center gap-4 ml-auto">
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
            <!-- Accès au compte -->
            <a href="/pro/compte">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
        </div>
    </div>
</header>

