<!-- 
    Composant du header pour le pro
    Pour l'ajouter, écrier la balise <div id='header-pro'></div> dans votre code html
-->
<header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black fixed top-0">
    <div class="flex w-full justify-between items-center">
        <a href="" onclick="toggleMenu()" class="flex gap-4 items-center">
            <i class="text-3xl fa-solid fa-bars"></i>
            <p>Menu</p>
        </a>
        <a href="/">
            <img src="/public/images/logo-PACT.svg" alt="[img] Logo-PACT">
        </a>
        <div class="flex gap-4 items-center">
            <a href="">
                <i class="text-3xl fa-solid fa-magnifying-glass"></i>
            </a>
            <a href="/pages/login-pro.php">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
            <?php if (activeLogout()) { ?>
                <a href="/php/membre/logout.php" onclick="return confirmLogout(event)">
                    <p>Se déconnecter</p>
                </a>
            <?php } ?>
        </div>
    </div>
</header>
