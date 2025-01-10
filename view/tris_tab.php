<!-- DROPDOWN MENU TRIS TABLETTE-->
<div class="hidden md:hidden relative" id="sort-section-tab">
    <div class="absolute top-0 right-0 z-20 self-end bg-white border border-base200 roundex-lg shadow-md max-w-48 p-2 flex flex-col gap-4">
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? '/' : '?sort=note-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'note-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note croissante</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? '/' : '?sort=note-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'note-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note décroissante</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix croissant</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix décroissant</p>
        </a>
    </div>
</div>