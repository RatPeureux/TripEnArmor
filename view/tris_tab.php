<!-- DROPDOWN MENU TRIS TABLETTE-->
<div class="hidden md:hidden relative" id="sort-section-tab">
    <div class="absolute top-0 right-0 z-20 self-end bg-white border border-black shadow-md max-w-48 p-2 flex flex-col gap-4">
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? strtok($_SERVER['PHP_SELF'], '?') : '?sort=note-ascending'; ?>" class="flex items-center hover:text-primary duration-100">
            <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? 'border-b border-primary' : ''; ?>">Note croissante</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? strtok($_SERVER['PHP_SELF'], '?') : '?sort=note-descending'; ?>" class="flex items-center hover:text-primary duration-100">
            <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? 'border-b border-primary' : ''; ?>">Note décroissante</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? strtok($_SERVER['PHP_SELF'], '?') : '?sort=price-ascending'; ?>" class="flex items-center hover:text-primary duration-100">
            <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? 'border-b border-primary' : ''; ?>">Prix croissant</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? strtok($_SERVER['PHP_SELF'], '?') : '?sort=price-descending'; ?>" class="flex items-center hover:text-primary duration-100">
            <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? 'border-b border-primary' : ''; ?>">Prix décroissant</p>
        </a>
    </div>
</div>