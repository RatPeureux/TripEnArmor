<!-- 
    Composant du header pour le pro
    Pour l'ajouter, écrier la balise <div id='header-pro'></div> dans votre code html
-->
<header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black fixed top-0">
    <div class="flex w-full justify-between items-center">
        <a href="#" onclick="toggleMenu()" class="flex gap-4 items-center">
            <i class="text-3xl fa-solid fa-bars"></i>
            <p>Menu</p>
        </a>
        <a href="/" class="flex gap-3 items-center">
            <img src="/public/images/logo.svg" alt="Logo" width="50">
            <h1 class="font-cormorant uppercase text-PACT">PACT Pro</h1>
        </a> 
        <div class="flex gap-10 items-center">
            <a href="" title="rechercher parmi mes offres">
                <i class="text-3xl fa-solid fa-magnifying-glass"></i>
            </a>
            <a href="/pro/connexion" title="me connecter/déconnecter">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
            <?php
            include dirname($_SERVER['DOCUMENT_ROOT']) . '/php-files/authentification.php';
            if (activeLogout()) { ?>
                <a href="/php/membre/logout.php" onclick="return confirmLogout(event)">
                    <p>Se déconnecter</p>
                </a>
            <?php } ?>
        </div>
    </div>
</header>