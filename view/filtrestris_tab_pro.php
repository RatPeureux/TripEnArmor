<!-- DROPDOWN MENU TRIS TABLETTE-->
<div class="hidden md:hidden relative" id="sort-section-tab">
    <div class="absolute top-0 right-0 z-20 self-end bg-white border border-base200 rounded-lg shadow-md max-w-48 p-2 flex flex-col gap-4">
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? '/pro' : '?sort=note-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'note-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note croissante</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? '/pro' : '?sort=note-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'note-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note décroissante</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? '/pro' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix croissant</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? '/pro' : '?sort=price-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix décroissant</p>
        </a>
    <?php if ($pro['data']['type'] === 'prive') { ?>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-ascending') ? '/pro' : '?sort=type-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Type d'offre de A à Z</p>
        </a>
        <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-descending') ? '/pro' : '?sort=type-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'type-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Type d'offre de Z à A</p>
        </a>
    <?php } ?>
    </div>
</div>

<!-- CHAMPS DE FILTRES TABLETTE -->
<div class="hidden md:hidden space-y-4 mr-6 mb-4 w-full" id="filter-section-tab">
    <div class="flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f1-tab">
            <p>Catégorie</p>
            <p id="arrow-f1-tab">></p>
        </div>
        <div class="hidden text-small flex flex-wrap gap-4" id="developped-f1-tab">
            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="restauration-tab" />
                <label for="restauration-tab">Restauration</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="activite-tab" />
                <label for="activite-tab">Activité</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="spectacle-tab" />
                <label for="spectacle-tab">Spectacle</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="visite-tab" />
                <label for="visite-tab">Visite</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="parc_attraction-tab" />
                <label for="parc_attraction-tab">Parc d'attraction</label>
            </div>
        </div>
    </div>
    <div class="flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f2-tab">
            <p>Disponibilité</p>
            <p id="arrow-f2-tab">></p>
        </div>
        <div class="hidden text-small flex flex-wrap gap-4" id="developped-f2-tab">
            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="open-tab" />
                <label for="open-tab">Ouvert</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="close-tab" />
                <label for="close-tab">Fermé</label>
            </div>
        </div>
    </div>
    <div class="flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f3-tab">
            <p>Localisation</p>
            <p id="arrow-f3-tab">></p>
        </div>
        <div class="hidden flex flex-wrap items-center gap-4" id="developped-f3-tab">
            <div class="text-nowrap text-small flex items-center gap-2 w-full">
                <label>Ville ou Code postal</label>
                <input id="localisation-tab" type="text" class="w-full border border-base300 rounded-lg p-1 focus:ring-0" />
            </div>
        </div>
    </div>
    <div class="flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f4-tab">
            <p>Note générale</p>
            <p id="arrow-f4-tab">></p>
        </div>
        <div class="hidden flex items-center" id="developped-f4-tab">
            <label class="text-small">Intervalle des notes entre&nbsp;</label>
            <div class="flex items-center">
                <input id="min-note-tab" type="number" value="0" min="0" max="5" step="0.5" class="w-[44px] border border-base300 rounded-lg p-1 text-small text-right focus:ring-0" />
                &nbsp;
                <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
            </div>
            <label class="text-small">&nbsp;et&nbsp;</label>
            <div class="flex items-center">
                <input id="max-note-tab" type="number" value="5" min="0" max="5" step="0.5" class="w-[44px] border border-base300 rounded-lg p-1 text-small text-right focus:ring-0" />
                &nbsp;
                <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
            </div>
        </div>
    </div>
    <div class="hidden flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f5-tab">
            <p>Période</p>
            <p id="arrow-f5-tab">></p>
        </div>
        <div class="hidden text-small flex items-center" id="developped-f5-tab">
            <label>Offre allant du&nbsp;</label>
            <input type="date" class="border border-base300 rounded-lg p-1 text-right mr-4" id="min-date-tab" name="min-date-tab">
            <label>&nbsp;au&nbsp;</label>
            <input type="date" class="border border-base300 rounded-lg p-1 text-right" id="max-date-tab" name="max-date-tab">
        </div>
    </div>
    <div class="flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f6-tab">
            <p>Prix</p>
            <p id="arrow-f6-tab">></p>
        </div>
        <div class="hidden flex items-center" id="developped-f6-tab">
            <label class="text-small">Intervalle des prix entre&nbsp;</label>
            <input id="min-price-tab" type="number" value="0" min="0" max="99" class="w-[44px] border border-base300 rounded-lg p-1 text-small text-right focus:ring-0" />
            <label class="text-small">&nbsp;€&nbsp;et&nbsp;</label>
            <input id="max-price-tab" type="number" value="<?php echo $prix_mini_max;?>" min="0" max="<?php echo $prix_mini_max;?>" class="w-[44px] border border-base300 rounded-lg p-1 text-small text-right focus:ring-0" />
            <label class="text-small">&nbsp;€</label>
        </div>
    </div>
<?php if ($pro['data']['type'] === 'prive') { ?>
    <div class="flex flex-col w-full bg-base100 border border-base200  p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f7-tab">
            <p>Type d'offre</p>
            <p id="arrow-f7-tab">></p>
        </div>
        <div class="hidden flex items-center gap-4" id="developped-f7-tab">
            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="standard-tab" />
                <label for="standard-tab">Standard</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mb-1" id="premium-tab" />
                <label for="premium-tab">Premium</label>
            </div>
        </div>
    </div>
<?php } ?>
</div>