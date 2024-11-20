<!--
    Composant menu du visiteur / membre
    Pour l'ajouter, écrier la balise <div id='menu' class='x'></div> dans votre code html
    La classe qui a l'unique valeur 'x' est un nombre entre 1 et 5, et permet de savoir quel menu est actif actuellement.
    Les valeurs de x et leur correspondance sont :
        1 - Accueil
        2 - Toutes les offres
        3 - À la une
        4 - Consultées récemment
        5 - Nouveautés
    (responsive)
-->

<!-- VERSION PHONE -->
<div class="md:hidden h-full bg-base100 fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
  <div class="p-4 flex flex-row gap-3 justify-start items-center h-20 border-b-2 border-black">
    <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleMenu()"></i>
    <h1 class="text-h1">Menu</h1>
  </div>
  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 border-b-2 border-black" href="/">Accueil</a>
    <a class="pl-5 py-3 border-b-2 border-black flex justify-between pr-2" href="/offre"> <!-- TODO: implémenter la logique suivante : -->
      <!-- La page `offre` affiche toutes les offres, c'est la page d'accueil en gros, sauf si il y a un "id" qui est set en mode "GET", dans ce cas c'est le détail de l'offre qui est affiché -->
      <p>Toutes les offres</p>
      <p>></p>
    </a>
    <a class="pl-10 py-3 border-b-2 border-black" href="/offre/a-la-une">À la une</a>
    <a class="pl-10 py-3 border-b-2 border-black" href="/offre/consultees-recemment">Consultées récemment</a>
    <a class="pl-10 py-3 border-b-2 border-black" href="/offre/nouveau">Nouveautés</a>
  </div>
  <div class="md:hidden h-full bg-white fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
    <div class="p-4 flex flex-row gap-3 justify-start items-center h-20 border-b-2 border-black">
      <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleMenu()"></i>
      <h1 class="text-h1">Menu</h1>
    </div>
    <div class="all-items flex flex-col items-stretch">
      <a class="pl-5 py-3 border-b-2 border-black" href="/">Accueil</a>
      <a class="pl-5 py-3 border-b-2 border-black flex justify-between pr-2" href="/offre">
        <p>Toutes les offres</p>
        <p>></p>
      </a>
      <a class="pl-10 py-3 border-b-2 border-black" href="/offre/a-la-une">À la une</a>
      <a class="pl-10 py-3 border-b-2 border-black" href="/offre/consultees-recemment">Consultées récemment</a>
      <a class="pl-10 py-3 border-b-2 border-black" href="/offre/nouveau">Nouveautés</a>
    </div>
  </div>
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>

<!-- VERSION TABLETTE OU PLUS (+768px) -->
<div class="hidden w-52 sticky top-2 md:block bg-white z-20 border-black border rounded-b-lg rounded-tr-lg">
  <div class="p-4 flex flex-row gap-3 justify-start items-center">
    <i class="text-3xl fa-solid fa-bars"></i>
    <h1 class="text-h1">Menu</h1>
  </div>
  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 border-t-2 border-black" href="/">Accueil</a>
    <a class="pl-5 py-3 border-t-2 border-black flex justify-between pr-2" href="/offre">
      <p>Toutes les offres</p>
      <p>></p>
    </a>
    <a class="pl-10 py-3 border-t-2 border-black" href="/offre/a-la-une">À la une</a>
    <a class="pl-10 py-3 border-t-2 border-black" href="/offre/consultees-recemment">Consultées récemment</a>
    <a class="pl-10 py-3 border-t-2 border-black" href="/offre/nouveau">Nouveautés</a>
  </div>
</div>