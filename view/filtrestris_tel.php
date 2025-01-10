<!-- BOUTONS DE FILTRES ET DE TRIS TÉLÉPHONE -->
<div class="block md:hidden p-4 h-16 w-full bg-blur/75 backdrop-blur border-t-2 border-black fixed bottom-0 flex items-center justify-between">
    <a class="p-2 flex items-center gap-2 hover:text-primary duration-100" onclick="toggleFiltres()">
        <i class="text xl fa-solid fa-filter"></i>
        <p>Filtrer</p>
    </a>

    <div>
        <a class="p-2 flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tel">
            <i class="text xl fa-solid fa-sort"></i>
            <p>Trier par</p>
        </a>
        <!-- DROPDOWN MENU TRIS TÉLÉPHONE -->
        <div class="hidden md:hidden absolute bottom-[72px] right-2 z-20 bg-white border border-base200 roundex-lg shadow-md max-w-48 p-2 flex flex-col gap-4" id="sort-section-tel">
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? '/' : '?sort=note-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'note-ascending') ? 'font-bolx' : ''; ?> hover:text-primary duration-100">
                <p>Note croissante</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? '/' : '?sort=note-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'note-descending') ? 'font-bolx' : ''; ?> hover:text-primary duration-100">
                <p>Note décroissante</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? 'font-bolx' : ''; ?> hover:text-primary duration-100">
                <p>Prix croissant</p>
            </a>
            <a href="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-ascending') ? 'font-bolx' : ''; ?> hover:text-primary duration-100">
                <p>Prix décroissant</p>
            </a>
        </div>
    </div>
</div>


<!-- MENU FILTRE TÉLÉPHONE -->
<div class="fixed block md:hidden top-0 flex flex-col justify-between w-full h-screen bg-base100 -translate-x-full duration-200 z-50" id="filtres">
    <div>
        <div class="p-4 gap-4 flex justify-start items-center h-20">
            <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleFiltres()"></i>
            <h1 class="text-h1">Filtres</h1>
        </div>

        <div class="w-full">
            <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f1-tel">
                    <p>Catégorie</p>
                    <p class="arrow" id="arrow-f1-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f1-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="restauration-tel" />
                        <label for="restauration-tel">Restauration</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="activite-tel" />
                        <label for="activite-tel">Activité</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="spectacle-tel" />
                        <label for="spectacle-tel">Spectacle</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="visite-tel" />
                        <label for="visite-tel">Visite</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="parc_attraction-tel" />
                        <label for="parc_attraction-tel">Parc d'attraction</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f2-tel">
                    <p>Disponibilité</p>
                    <p class="arrow" id="arrow-f2-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f2-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" class="mb-1" id="ouvert-tel" />
                        <label for="ouvert-tel">Ouvert</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="ferme-tel" />
                        <label for="ferme-tel">Fermé</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f3-tel">
                    <p>Localisation</p>
                    <p class="arrow" id="arrow-f3-tel">></p>
                </div>
                <div class="developped hidden flex flex-nowrap w-full items-center gap-4" id="developped-f3-tel">
                    <div class="text-nowrap text-small flex items-center gap-2 w-full">
                        <label>Ville ou Code postal</label>
                        <input id="localisation-tel" type="text" class="w-full border border-base300 roundex-lg p-1 focus:ring-0" />
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f4-tel">
                    <p>Note générale</p>
                    <p class="arrow" id="arrow-f4-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f4-tel">
                    <label class="text-small">Intervalle des prix entre&nbsp;</label>
                    <div class="flex items-center">
                        <input id="min-note-tel" type="number" value="0" min="0" max="5" step="0.5" class="border border-base300 roundex-lg p-1 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                    <label class="text-small">&nbsp;et&nbsp;</label>
                    <div class="flex items-center">
                        <input id="max-note-tel" type="number" value="5" min="0" max="5" step="0.5" class="border border-base300 roundex-lg p-1 text-small text-right w-[39px] focus:ring-0" />
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11">
                    </div>
                </div>
            </div>
            <div class="hidden flex flex-col w-full border-t-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f5-tel">
                    <p>Période</p>
                    <p class="arrow" id="arrow-f5-tel">></p>
                </div>
                <div class="developped text-small hidden flex flex-wrap items-center" id="developped-f5-tel">
                    <div>
                        <label>Offre allant du&nbsp;</label>
                        <input type="date" class="border border-base300 roundex-lg p-1 text-right mr-4" id="min-date-tel" name="min-date-tel">
                        &nbsp;
                    </div>
                    <div>
                        <label>au&nbsp;</label>
                        <input type="date" class="border border-base300 roundex-lg p-1 text-right" id="max-date-tel" name="max-date-tel">
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full border-t-2 border-black p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f6-tel">
                    <p>Prix</p>
                    <p class="arrow" id="arrow-f6-tel">></p>
                </div>
                <div class="developped hidden flex flex-wrap items-center justify-between gap-2" id="developped-f6-tel">
                    <div class="flex items-center">
                        <label class="text-small">Intervalle des prix entre&nbsp;</label>
                        <input id="min-price-tel" type="number" value="0" min="0" max="99" class="w-[44px] border border-base300 roundex-lg p-1 text-small text-right focus:ring-0" />
                        <label class="text-small">&nbsp;€&nbsp;et&nbsp;</label>
                        <input id="max-price-tel" type="number" value="<?php echo $prix_mini_max;?>" min="0" max="<?php echo $prix_mini_max;?>" class="w-[44px] border border-base300 roundex-lg p-1 text-small text-right focus:ring-0" />
                        <label class="text-small">&nbsp;€</label>
                    </div>
                    <div class="text-small flex flex-wrap gap-4" id="developped-f2-tel">
                        <label class="text-small">Restauration :&nbsp;</label>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€-tel" />
                            <label for="€-tel">€</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€€-tel" />
                            <label for="€€-tel">€€</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€€€-tel" />
                            <label for="€€€-tel">€€€</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a class="fixed left-0 right-0 bottom-0 roundex bg-primary font-bolx uppercase text-h4 text-white text-center m-2 p-4 cursor-pointer hover:bg-orange-600" onclick="toggleFiltres()">
        Valider
    </a>
</div>