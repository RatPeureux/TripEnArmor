<!-- BOUTONS DE FILTRES ET DE TRIS TÉLÉPHONE -->
<div class="block md:hidden p-4 h-16 w-full bg-bgBlur/75 backdrop-blur border-t-2 border-black fixed bottom-0 flex items-center justify-between">
    <a href="#" class="p-2 flex items-center gap-2 hover:text-primary duration-100" onclick="toggleFiltres()">
        <i class="text xl fa-solid fa-filter"></i>
        <p>Filtrer</p>
    </a>

    <div>
        <a href="#" class="p-2 flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tel">
            <i class="text xl fa-solid fa-sort"></i>
            <p>Trier par</p>
        </a>
        <!-- DROPDOWN MENU TRIS TÉLÉPHONE -->
        <div class="hidden md:hidden absolute bottom-[72px] right-2 z-20 bg-white border border-base200 rounded-lg shadow-md max-w-48 p-2 flex flex-col gap-4" id="sort-section-tel">
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                <p>Note croissante</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                <p>Note décroissante</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                <p>Prix croissant</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                <p>Prix décroissant</p>
            </a>   
        <?php if ($pro['data']['type'] === 'prive') { ?>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-ascending') ? '/' : '?sort=type-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                <p>Type d'offre de A à Z</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-descending') ? '/' : '?sort=type-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'type-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                <p>Type d'offre de Z à A</p>
            </a>
        <?php } ?>
        </div>
    </div>
</div>