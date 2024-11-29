<!-- 
    Composant du header pour le pro
    Pour l'ajouter, écrier la balise <div id='header-pro'></div> dans votre code html
-->
<header class="z-30 w-full bg-bgBlur/75 backdrop-blur flex justify-center p-4 h-20 border-b-2 border-black fixed top-0">
    <div class="flex w-full justify-between items-center">
        <a href="#" onclick="toggleMenu()" class="flex gap-4 items-center hover:text-primary duration-100">
            <i class="text-3xl fa-solid fa-bars"></i>
            <p class="hidden sm:block sm:mr-36">Menu</p>
        </a>
        <a href="/" class="flex gap-3 items-center">
            <img src="/public/images/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase whitespace-nowrap text-PACT">
                <span class="hidden sm:inline">PACT</span> Pro
            </h1>
        </a>
        <div class="flex gap-10 items-center">
            <a href="#" class="hidden sm:block" title="rechercher parmi mes offres">
                <i class="text-3xl fa-solid fa-magnifying-glass hover:text-primary duration-100"></i>
            </a>
            <!-- Si connecté -->
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
            if (isConnectedAsPro()) { ?>
                <div class="flex items-center gap-4">
                    <a href="/pro/compte">
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
                <a href="/pro/connexion" class="hover:text-primary duration-100">
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