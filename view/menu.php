<!--
    Composant menu du visiteur / membre
    (responsive)
-->

<!-- VERSION PHONE -->
<div class="md:hidden h-full bg-base100 fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
  <a class="mt-4 mx-2 mb-1 self-end flex items-center gap-2">
      <i class="fa-solid fa-bars"></i>
      <p>Menu</p>
  </a>

  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 1) {
      echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="pl-5 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 2) {
      echo 'active';
    } ?>" href="/offres/a-la-une">À la Une</a>
    <a class="pl-5 py-3 border-t border-black  <?php if (isset($pagination) && $pagination == 3) {
      echo 'active';
    } ?>" href="/offres">Toutes les offres</a>
    <!-- <a class="pl-10 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 4) {
      echo 'active';
    } ?>" href="/offre/consultees-recemment">Consultées récemment</a>
    <a class="pl-10 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 5) {
      echo 'active';
    } ?>" href="/offre/nouveau">Nouveautés</a>
  </div> -->
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>

<!-- VERSION TABLETTE OU PLUS (+768px) -->
<div class="hidden w-52 sticky top-2 md:block bg-white z-20 border-black border  ">
  <a class="mt-4 mx-2 mb-1 self-end flex items-center gap-2">
      <i class="fa-solid fa-bars"></i>
      <p>Menu</p>
  </a>

  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 1) {
      echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="pl-10 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 3) {
      echo 'active';
    } ?>" href="/offres/a-la-une">À la Une</a>
    <a class="pl-10 py-3 border-t border-black  <?php if (isset($pagination) && $pagination == 2) {
      echo 'active';
    } ?>" href="/offres">Toutes les offres</a>
    <!-- <a class="pl-10 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 4) {
      echo 'active';
    } ?>" href="/offre/consultees-recemment">Consultées récemment</a>
    <a class="pl-10 py-3 border-t border-black <?php if (isset($pagination) && $pagination == 5) {
      echo 'active';
    } ?>" href="/offre/nouveau">Nouveautés</a> -->
  </div>
</div>