<!-- 
    Composant du header pour les visiteurs / membres
    Pour l'ajouter, écrier la balise <div id='header'></div> dans votre code html
    (responsive)
-->
<header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
    <div class="flex w-full justify-between items-center">
        <a href="" onclick="toggleMenu()" class="md:hidden">
            <i class="text-3xl fa-solid fa-bars"></i>
        </a>
        <a href="/">
            <img src="/public/images/logo-PACT.svg" alt="[img] Logo-PACT">
        </a>
        <div class="flex gap-4 items-center">  
            <a href="/pages/login-membre.php">
                <i class="text-3xl fa-regular fa-user"></i>
            </a>
            <?php 
            
            include("../../php/authentification.php");
            if (activeLogout()) { ?>
                <a href="/php/membre/logout.php" onclick="return confirmLogout(event)">
                    <p>Se déconnecter</p>
                </a>
            <?php } ?>
        </div>
    </div>
</header>
