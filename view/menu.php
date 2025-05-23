<!-- VERSION PHONE -->
<div class="md:hidden h-full bg-white fixed top-0 w-7/12 left-0 -translate-x-full duration-200 z-50">
  <a class="p-4 gap-4 self-end flex items-center">
    <i class="text-3xl fa-solid fa-circle-xmark cursor-pointer" onclick="toggleMenu()"></i>
    <h1 class="text-3xl">Menu</h1>
  </a>

  <div class="all-items flex flex-col items-stretch">
    <a class="p-3 <?php if (isset($pagination) && $pagination == 1) {
      echo 'active';
    } ?>" href="/">Accueil</a>
    <a class="p-3 <?php if (isset($pagination) && $pagination == 2) {
      echo 'active';
    } ?>" href="/offres/a-la-une">À la Une</a>
    <a class="p-3  <?php if (isset($pagination) && $pagination == 3) {
      echo 'active';
    } ?>" href="/offres/nouveautes">Nouveautés</a>
    <a class="p-3  <?php if (isset($pagination) && $pagination == 4) {
      echo 'active';
    } ?>" href="/offres/recentes">Récentes</a>
    <a class="p-3  <?php if (isset($pagination) && $pagination == 5) {
      echo 'active';
    } ?>" href="/offres">Toutes les offres</a>
  </div>
</div>

<div id="layer-background" onclick="toggleMenu()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>

<!-- VERSION TABLETTE OU PLUS (+768px) -->
<div class="hidden w-52 sticky top-2 md:block bg-white z-20">
  <a class="mt-4 mx-2 mb-1 self-end flex items-center gap-2 cursor-pointer hover:text-primary" id="menu-button"
    tabindex="0">
    <i class="fa-solid fa-bars"></i>
    <p>Menu</p>
  </a>

  <div class="w-52 border-base200 border-t z-25" id="menu-component">
    <div class="all-items flex flex-col items-stretch">
      <div class="p-3">
        <a class="<?php if (isset($pagination) && $pagination == 1) {
          echo 'active';
        } ?>" href="/">Accueil</a>
      </div>
      <div class="p-3">
        <a class="<?php if (isset($pagination) && $pagination == 2) {
          echo 'active';
        } ?>" href="/offres/a-la-une">À la Une</a>
      </div>
      <div class="p-3">
        <a class="<?php if (isset($pagination) && $pagination == 3) {
          echo 'active';
        } ?>" href="/offres/nouveautes">Nouveautés</a>
      </div>
      <div class="p-3">
        <a class="<?php if (isset($pagination) && $pagination == 4) {
          echo 'active';
        } ?>" href="/offres/recentes">Récentes</a>
      </div>
      <div class="p-3">
        <a class="<?php if (isset($pagination) && $pagination == 5) {
          echo 'active';
        } ?>" href="/offres">Toutes les offres</a>
      </div>
    </div>
  </div>

  <!-- AJOUTER LES FILTRES AU MENU SI BESOIN -->
  <?php if (isset($menu_avec_filtres) && $menu_avec_filtres) { ?>
    <a class="mt-6 mx-2 mb-1 self-end flex items-center gap-2 cursor-pointer hover:text-primary" id="filtre-button"
      tabindex="0">
      <i class="text xl fa-solid fa-filter"></i>
      <p>Filtrer</p>
    </a>

    <div class="hidden w-52 border-base200 border-t z-25" id="filtre-component">
      <div class="flex flex-col w-full p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f1-tab" tabindex="0">
          <p>Catégorie</p>
          <p class="arrow" id="arrow-f1-tab">></p>
        </div>
        <div class="alldevelopped hidden flex text-sm flex-wrap gap-4" id="developped-f1-tab">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="restauration-tab">
            <label for="restauration-tab">Restauration</label>
          </div>

          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="activite-tab">
            <label for="activite-tab">Activité</label>
          </div>

          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="spectacle-tab">
            <label for="spectacle-tab">Spectacle</label>
          </div>

          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="visite-tab">
            <label for="visite-tab">Visite</label>
          </div>

          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="parc_attraction-tab">
            <label for="parc_attraction-tab">Parc d'attraction</label>
          </div>
        </div>
      </div>
      <div class="flex flex-col w-full p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f2-tab" tabindex="0">
          <p>Disponibilité</p>
          <p class="arrow" id="arrow-f2-tab">></p>
        </div>
        <div class="alldevelopped hidden flex text-sm flex-wrap gap-4" id="developped-f2-tab">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="ouvert-tab">
            <label for="ouvert-tab">Ouvert</label>
          </div>

          <div class="flex items-center gap-2">
            <input type="checkbox" class="mb-1" id="ferme-tab">
            <label for="ferme-tab">Fermé</label>
          </div>
        </div>
      </div>
      <div class="flex flex-col w-full p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f3-tab" tabindex="0">
          <p>Localisation</p>
          <p class="arrow" id="arrow-f3-tab">></p>
        </div>
        <div class="alldevelopped hidden flex flex-col w-full" id="developped-f3-tab">
          <label class="text-sm">Ville ou Code postal</label>
          <input id="localisation-tab" type="text" class="w-full border border-black p-1 focus:ring-0">
        </div>
      </div>
      <div class="flex flex-col w-full p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f4-tab" tabindex="0">
          <p>Note générale</p>
          <p class="arrow" id="arrow-f4-tab">></p>
        </div>
        <div class="alldevelopped hidden flex-col" id="developped-f4-tab">
          <label class="text-sm">Intervalle des notes entre&nbsp;</label>
          <div class="flex items-center">
            <div class="flex items-center">
              <input id="min-note-tab" type="number" value="0" min="0" max="5" step="0.5"
                class="w-[44px] border border-black p-1 text-sm text-right focus:ring-0">
              &nbsp;
              <img src="/public/icones/egg-full.svg" class="mb-1" width="11" alt='1 point de note'>
            </div>
            <label class="text-sm">&nbsp;et&nbsp;</label>
            <div class="flex items-center">
              <input id="max-note-tab" type="number" value="5" min="0" max="5" step="0.5"
                class="w-[44px] border border-black p-1 text-sm text-right focus:ring-0">
              &nbsp;
              <img src="/public/icones/egg-full.svg" class="mb-1" width="11" alt='1 point de note'>
            </div>
          </div>
        </div>
      </div>
      <div class="hidden flex flex-col w-full p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f5-tab" tabindex="0">
          <p>Période</p>
          <p class="arrow" id="arrow-f5-tab">></p>
        </div>
        <div class="alldevelopped text-sm hidden flex flex-wrap items-center" id="developped-f5-tab">
          <div>
            <label>Offre allant du&nbsp;</label>
            <input type="date" class="border border-black p-1 text-right mr-4" id="min-date-tab" name="min-date-tab">
            &nbsp;
          </div>
          <div>
            <label>au&nbsp;</label>
            <input type="date" class="border border-black p-1 text-right" id="max-date-tab" name="max-date-tab">
          </div>
        </div>
      </div>
      <div class="flex flex-col w-full p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f6-tab" tabindex="0">
          <p>Prix</p>
          <p class="arrow" id="arrow-f6-tab">></p>
        </div>
        <div class="alldevelopped hidden flex flex-wrap items-center justify-between gap-2" id="developped-f6-tab">
          <div class="flex flex-col">
            <label class="text-sm">Intervalle des prix entre&nbsp;</label>
            <div class="flex items-center">
              <input id="min-price-tab" type="number" value="0" min="0" max="99"
                class="w-[44px] border border-black p-1 text-sm text-right focus:ring-0">
              <label class="text-sm">&nbsp;€&nbsp;et&nbsp;</label>
              <input id="max-price-tab" type="number" value="<?php echo $prix_mini_max; ?>" min="0"
                max="<?php echo $prix_mini_max; ?>"
                class="w-[44px] border border-black p-1 text-sm text-right focus:ring-0">
              <label class="text-sm">&nbsp;€</label>
            </div>
          </div>
          <div class="text-sm flex flex-wrap">
            <label class="text-sm">Restauration :&nbsp;</label>
            <div class="w-full flex items-center justify-between">
              <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="€-tab">
                <label for="€-tab">€</label>
              </div>

              <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="€€-tab">
                <label for="€€-tab">€€</label>
              </div>

              <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="€€€-tab">
                <label for="€€€-tab">€€€</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="/scripts/filtersAndSorts.js"></script>
  <?php } ?>
</div>

<!-- Réinitialiser des variables pour éviter les conflits avec les prochaines inclusions de headers -->
<?php
unset($pagination, $menu_avec_filtres);
?>

<script>
  const menu = document.getElementById('menu-component');
  const menuButton = document.getElementById('menu-button');

  // ACTIVER / DESACTIVER LE MENU
  menuButton.addEventListener('click', function () {
    if (menu.classList.contains('hidden')) {
      menu.classList.remove('hidden');
    } else {
      menu.classList.add('hidden');
    }
  });

  menuButton.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
      } else {
        menu.classList.add('hidden');
      }
    }
  });

  // PARTIE FILTRES
  const filtre = document.getElementById('filtre-component');
  const filtreButton = document.getElementById('filtre-button');

  filtreButton?.addEventListener('click', function () {
    if (filtre.classList.contains('hidden')) {
      filtre.classList.remove('hidden');
    } else {
      filtre.classList.add('hidden');
    }
  });

  filtreButton?.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
      if (filtre.classList.contains('hidden')) {
        filtre.classList.remove('hidden');
      } else {
        filtre.classList.add('hidden');
      }
    }
  });
</script>