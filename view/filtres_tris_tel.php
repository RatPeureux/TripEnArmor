<!-- BOUTONS DE FILTRES ET DE TRIS TÉLÉPHONE -->
<div class="block md:hidden p-4 h-16 w-full bg-blur/25 backdrop-blur fixed border-t border-black bottom-0 flex items-center justify-between">
    <a class="cursor-pointer p-2 flex items-center gap-2 hover:text-primary duration-100" onclick="toggleFiltres()">
        <i class="text xl fa-solid fa-filter"></i>
        <p>Filtrer</p>
    </a>

    <div>
        <a class="cursor-pointer p-2 flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tel">
            <i class="text xl fa-solid fa-sort"></i>
            <p>Trier par</p>
        </a>
        <!-- DROPDOWN MENU TRIS TÉLÉPHONE -->
        <div class="hidden md:hidden absolute bottom-[72px] right-2 z-20 bg-white border border-black shadow-md max-w-48 p-2 flex flex-col gap-4" id="sort-section-tel">
            <a href="<?php
                // Base URL sans les paramètres
                $baseUrl = strtok($_SERVER['PHP_SELF'], '?');

                // Initialisation des paramètres
                $params = $_GET;

                // Si le tri est déjà défini comme "note-ascending", on enlève le tri
                if (isset($params['sort']) && $params['sort'] === 'note-ascending') {
                    unset($params['sort']);
                } else {
                    // Ajouter ou mettre à jour le paramètre 'sort'
                    $params['sort'] = 'note-ascending';
                }

                // Construire l'URL avec les nouveaux paramètres
                $urlWithParams = $baseUrl . '?' . http_build_query($params);

                echo htmlspecialchars($urlWithParams);
            ?>" class="flex items-center hover:text-primary duration-100">
                <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? 'border-b border-primary' : ''; ?>">Note croissante</p>
            </a>
            <a href="<?php
                // Base URL sans les paramètres
                $baseUrl = strtok($_SERVER['PHP_SELF'], '?');

                // Initialisation des paramètres
                $params = $_GET;

                // Si le tri est déjà défini comme "note-ascending", on enlève le tri
                if (isset($params['sort']) && $params['sort'] === 'note-descending') {
                    unset($params['sort']);
                } else {
                    // Ajouter ou mettre à jour le paramètre 'sort'
                    $params['sort'] = 'note-descending';
                }

                // Construire l'URL avec les nouveaux paramètres
                $urlWithParams = $baseUrl . '?' . http_build_query($params);

                echo htmlspecialchars($urlWithParams);
            ?>" class="flex items-center hover:text-primary duration-100">
                <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? 'border-b border-primary' : ''; ?>">Note décroissante</p>
            </a>
            <a href="<?php
                // Base URL sans les paramètres
                $baseUrl = strtok($_SERVER['PHP_SELF'], '?');

                // Initialisation des paramètres
                $params = $_GET;

                // Si le tri est déjà défini comme "note-ascending", on enlève le tri
                if (isset($params['sort']) && $params['sort'] === 'price-ascending') {
                    unset($params['sort']);
                } else {
                    // Ajouter ou mettre à jour le paramètre 'sort'
                    $params['sort'] = 'price-ascending';
                }

                // Construire l'URL avec les nouveaux paramètres
                $urlWithParams = $baseUrl . '?' . http_build_query($params);

                echo htmlspecialchars($urlWithParams);
            ?>" class="flex items-center hover:text-primary duration-100">
                <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? 'border-b border-primary' : ''; ?>">Prix croissant</p>
            </a>
            <a href="<?php
                // Base URL sans les paramètres
                $baseUrl = strtok($_SERVER['PHP_SELF'], '?');

                // Initialisation des paramètres
                $params = $_GET;

                // Si le tri est déjà défini comme "note-ascending", on enlève le tri
                if (isset($params['sort']) && $params['sort'] === 'price-descending') {
                    unset($params['sort']);
                } else {
                    // Ajouter ou mettre à jour le paramètre 'sort'
                    $params['sort'] = 'price-descending';
                }

                // Construire l'URL avec les nouveaux paramètres
                $urlWithParams = $baseUrl . '?' . http_build_query($params);

                echo htmlspecialchars($urlWithParams);
            ?>" class="flex items-center hover:text-primary duration-100">
                <p class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? 'border-b border-primary' : ''; ?>">Prix décroissant</p>
            </a>
        </div>
    </div>
</div>


<!-- MENU FILTRE TÉLÉPHONE -->
<div class="fixed block md:hidden top-0 flex flex-col justify-between w-7/12 h-screen bg-white -translate-x-full duration-200 z-50" id="filtres">
    <div>
        <div class="p-4 gap-4 flex justify-start items-center h-20">
            <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleFiltres()"></i>
            <h1 class="text-h1">Filtres</h1>
        </div>

        <div class="w-full">
            <div class="flex flex-col w-full p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f1-tel">
                    <p>Catégorie</p>
                    <p class="arrow" id="arrow-f1-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f1-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="restauration-tel">
                        <label for="restauration-tel">Restauration</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="activite-tel">
                        <label for="activite-tel">Activité</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="spectacle-tel">
                        <label for="spectacle-tel">Spectacle</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="visite-tel">
                        <label for="visite-tel">Visite</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="parc_attraction-tel">
                        <label for="parc_attraction-tel">Parc d'attraction</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f2-tel">
                    <p>Disponibilité</p>
                    <p class="arrow" id="arrow-f2-tel">></p>
                </div>
                <div class="developped hidden text-small flex flex-wrap gap-4 developped-f2-tel">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="ouvert-tel">
                        <label for="ouvert-tel">Ouvert</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="ferme-tel">
                        <label for="ferme-tel">Fermé</label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f3-tel">
                    <p>Localisation</p>
                    <p class="arrow" id="arrow-f3-tel">></p>
                </div>
                <div class="developped hidden flex flex-nowrap w-full items-center gap-4" id="developped-f3-tel">
                    <div class="text-nowrap text-small flex items-center gap-2 w-full">
                        <label>Ville ou Code postal</label>
                        <input id="localisation-tel" type="text" class="w-full border border-black p-1 focus:ring-0">
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f4-tel">
                    <p>Note générale</p>
                    <p class="arrow" id="arrow-f4-tel">></p>
                </div>
                <div class="developped hidden flex items-center" id="developped-f4-tel">
                    <label class="text-small">Intervalle des prix entre&nbsp;</label>
                    <div class="flex items-center">
                        <input id="min-note-tel" type="number" value="0" min="0" max="5" step="0.5" class="border border-black p-1 text-small text-right w-[39px] focus:ring-0">
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11" alt='oeuf plein'>
                    </div>
                    <label class="text-small">&nbsp;et&nbsp;</label>
                    <div class="flex items-center">
                        <input id="max-note-tel" type="number" value="5" min="0" max="5" step="0.5" class="border border-black p-1 text-small text-right w-[39px] focus:ring-0">
                        &nbsp;
                        <img src="/public/icones/egg-full.svg" class="mb-1" width="11" alt='oeuf plein'>
                    </div>
                </div>
            </div>
            <div class="hidden flex flex-col w-full p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f5-tel">
                    <p>Période</p>
                    <p class="arrow" id="arrow-f5-tel">></p>
                </div>
                <div class="developped text-small hidden flex flex-wrap items-center" id="developped-f5-tel">
                    <div>
                        <label>Offre allant du&nbsp;</label>
                        <input type="date" class="border border-black p-1 text-right mr-4" id="min-date-tel" name="min-date-tel">
                        &nbsp;
                    </div>
                    <div>
                        <label>au&nbsp;</label>
                        <input type="date" class="border border-black p-1 text-right" id="max-date-tel" name="max-date-tel">
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f6-tel">
                    <p>Prix</p>
                    <p class="arrow" id="arrow-f6-tel">></p>
                </div>
                <div class="developped hidden flex flex-wrap items-center justify-between gap-2" id="developped-f6-tel">
                    <div class="flex items-center">
                        <label class="text-small">Intervalle des prix entre&nbsp;</label>
                        <input id="min-price-tel" type="number" value="0" min="0" max="99" class="w-[44px] border border-black p-1 text-small text-right focus:ring-0">
                        <label class="text-small">&nbsp;€&nbsp;et&nbsp;</label>
                        <input id="max-price-tel" type="number" value="<?php echo $prix_mini_max;?>" min="0" max="<?php echo $prix_mini_max;?>" class="w-[44px] border border-black p-1 text-small text-right focus:ring-0">
                        <label class="text-small">&nbsp;€</label>
                    </div>
                    <div class="text-small flex flex-wrap gap-4 developped-f2-tel">
                        <label class="text-small">Restauration :&nbsp;</label>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€-tel">
                            <label for="€-tel">€</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€€-tel">
                            <label for="€€-tel">€€</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="€€€-tel">
                            <label for="€€€-tel">€€€</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a class="bg-primary text-h4 text-white text-center m-4 p-2 cursor-pointer hover:bg-orange-600" onclick="toggleFiltres()">
        Rechercher
    </a>
</div>

<div id="layer-background-filtres" onclick="toggleFiltres()" class="hidden fixed w-full h-full top-0 left-0 z-40"></div>
