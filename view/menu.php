<!--
    Composant menu du visiteur / membre
    (responsive)
-->

<!-- VERSION PHONE -->
<div class="md:hidden h-full bg-white fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
  <a class="p-4 gap-4 self-end flex items-center">
    <i class="text-3xl fa-solid fa-circle-xmark cursor-pointer" onclick="toggleMenu()"></i>
    <h1 class="text-3xl">Menu</h1>
  </a>

  <div class="all-items flex flex-col items-stretch">
    <a class="pl-5 py-3 <?php if (isset($pagination) && $pagination == 1) {
        echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="pl-5 py-3 <?php if (isset($pagination) && $pagination == 2) {
        echo 'active';
    } ?>" href="/offres/a-la-une">À la Une</a>
    <a class="pl-5 py-3  <?php if (isset($pagination) && $pagination == 3) {
        echo 'active';
    } ?>" href="/offres">Toutes les offres</a>
  </div>
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>

<!-- VERSION TABLETTE OU PLUS (+768px) -->
<div class="hidden w-52 sticky top-2 md:block bg-white z-20">
  <a class="mt-4 mx-2 mb-1 self-end flex items-center gap-2 cursor-pointer hover:text-primary" id="menu-button"
    onclick="developpedMenu()">
    <i class="fa-solid fa-bars"></i>
    <p>Menu</p>
  </a>

  <div class="all-items flex flex-col items-stretch border-base200 border-t" id="menu-component">
    <a class="pl-5 py-3 border-black <?php if (isset($pagination) && $pagination == 1) {
        echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="pl-5 py-3 <?php if (isset($pagination) && $pagination == 3) {
        echo 'active';
    } ?>" href="/offres/a-la-une">À la Une</a>
    <a class="pl-5 py-3  <?php if (isset($pagination) && $pagination == 2) {
        echo 'active';
    } ?>" href="/offres">Toutes les offres</a>
  </div>
</div>

<script>
  function developpedMenu() {
    const menu = document.getElementById('menu-component');
    const menuButton = document.getElementById('menu-button');

    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');
    } else {
      menu.classList.add('hidden');
    }
  }
</script>