<!-- 
    Composant du header pour le pro
    Pour l'ajouter, écrier la balise <div id='header-pro'></div> dans votre code html
-->
<header class="w-full bg-white flex items-center px-4 h-16">
    <div class="flex w-full items-center justify-between relative mx-auto">
        <!-- Partie gauche -->
        <a href="/pro" class="flex gap-2 items-center">
            <img src="/public/icones/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase whitespace-nowrap text-PACT">
                <span class="hidden md:inline">PACT</span> PRO
            </h1>
        </a>

        <!-- Barre de recherche -->
        <div class="relative flex-1 max-w-lg mx-4">
            <div class="w-full relative flex items-center">
                <input type="text" id="search-field" placeholder="Rechercher par tags..."
                    class="rounded-full w-full border border-primary p-2 pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200"
                    aria-label="Recherche" autocomplete="off">
                <div class="absolute right-4 flex items-center justify-center transform -translate-y-1/2">
                    <i class="fa-solid fa-magnifying-glass fa-lg cursor-pointer" id="search-btn"></i>
                </div>
                <!-- Bouton de suppression -->
                <button class="hidden absolute right-2 min-w-max flex items-center justify-center bg-white  px-2 py-1"
                    id="clear-tags-btn">
                    <i class="text-xl fa-solid fa-times cursor-pointer"></i>
                </button>
            </div>
            <!-- Dropdown de recherche -->
            <div class="absolute top-full left-0 right-0 bg-white border border-base200  shadow-md mt-2 hidden z-10"
                id="search-menu"></div>
        </div>

        <!-- Partie droite -->
        <div class="flex items-center gap-4">
            <a href="/pro/compte">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
        </div>
    </div>
</header>

<script src="/scripts/filtersAndSortsPro.js"></script>