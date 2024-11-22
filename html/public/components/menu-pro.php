<!--
    Composant menu du pro
    Pour l'ajouter, écrier la balise <div id='menu-pro' class='x'></div> dans votre code html
    La classe qui a l'unique valeur 'x' est un nombre entre 1 et 5, et permet de savoir quel menu est actif actuellement.
    Les valeurs de x et leur correspondance sont :
        1 - Accueil
        ...
-->
<div class="h-full bg-base100 fixed top-0 w-1/4 left-0 -translate-x-full duration-200 z-50">
    <div class="p-4 flex flex-row gap-3 justify-start items-center h-20 border-b-2 border-black">
        <i class="text-3xl fa-solid fa-circle-xmark cursor-pointer" onclick="toggleMenu()"></i>
        <h1 class="text-h1">Menu</h1>
    </div>
    <div class="all-items flex flex-col items-stretch">
        <a class="pl-5 py-3 border-b-2 border-black" href="/pro">Accueil</a>
        <a class="pl-5 py-3 border-b-2 border-black" href="/pro">...</a>
    </div>
</div>
<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>