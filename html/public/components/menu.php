<!--
    Composant menu du visiteur / membre
    (responsive)
-->

<!-- VERSION PHONE -->
<div class="md:hidden h-full bg-base100 fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
  <div class="p-4 flex flex-row gap-3 justify-start items-center h-20 border-b-2 border-black <?php if (!isset($pagination)) {
    echo 'bg-primary text-white';
  } ?>">
    <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleMenu()"></i>
    <h1 class="text-h1">Menu</h1>
  </div>
  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 border-b-2 border-black <?php if (isset($pagination) && $pagination == 1) {
      echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="pl-5 py-3 border-b-2 border-black flex justify-between pr-2 <?php if (isset($pagination) && $pagination == 2) {
      echo 'active';
    } ?>" href="/offre">
      <p>Toutes les offres</p>
      <p>></p>
    </a>
    <a class="pl-10 py-3 border-b-2 border-black <?php if (isset($pagination) && $pagination == 3) {
      echo 'active';
    } ?>" href="/offre/a-la-une">À la une</a>
    <a class="pl-10 py-3 border-b-2 border-black <?php if (isset($pagination) && $pagination == 4) {
      echo 'active';
    } ?>" href="/offre/consultees-recemment">Consultées récemment</a>
    <a class="pl-10 py-3 border-b-2 border-black <?php if (isset($pagination) && $pagination == 5) {
      echo 'active';
    } ?>" href="/offre/nouveau">Nouveautés</a>
  </div>
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>

<!-- VERSION TABLETTE OU PLUS (+768px) -->
<div class="hidden w-52 sticky top-2 md:block bg-white z-20 border-black border rounded-b-lg rounded-tr-lg">
  <div class="p-4 flex flex-row gap-3 justify-start items-center <?php if (!isset($pagination)) {
    echo 'bg-primary text-white';
  } ?>">
    <i class="text-3xl fa-solid fa-bars"></i>
    <h1 class="text-h1">Menu</h1>
  </div>
  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 1) {
      echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="pl-5 py-3 border-t-2 border-black flex justify-between pr-2 <?php if (isset($pagination) && $pagination == 2) {
      echo 'active';
    } ?>" href="/">
      <p>Toutes les offres</p>
      <p>></p>
    </a>
    <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 3) {
      echo 'active';
    } ?>" href="/offre/a-la-une">À la une</a>
    <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 4) {
      echo 'active';
    } ?>" href="/offre/consultees-recemment">Consultées récemment</a>
    <a class="pl-10 py-3 border-t-2 border-black <?php if (isset($pagination) && $pagination == 5) {
      echo 'active';
    } ?>" href="/offre/nouveau">Nouveautés</a>
  </div>
</div>