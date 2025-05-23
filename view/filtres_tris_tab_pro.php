<!-- DROPDOWN MENU TRIS TABLETTE-->
<div class="hidden md:hidden relative z-50" id="sort-section-tab">
    <div
        class="absolute top-0 right-0 self-end bg-white border border-black shadow-md max-w-48 p-2 flex flex-col gap-4">
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
            <p
                class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-ascending') ? 'border-b border-primary' : ''; ?>">
                Note croissante</p>
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
            <p
                class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'note-descending') ? 'border-b border-primary' : ''; ?>">
                Note décroissante</p>
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
            <p
                class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-ascending') ? 'border-b border-primary' : ''; ?>">
                Prix croissant</p>
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
            <p
                class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price-descending') ? 'border-b border-primary' : ''; ?>">
                Prix décroissant</p>
        </a>
        <?php if ($pro['data']['type'] === 'prive') { ?>
                <a href="<?php
                // Base URL sans les paramètres
                $baseUrl = strtok($_SERVER['PHP_SELF'], '?');

                // Initialisation des paramètres
                $params = $_GET;

                // Si le tri est déjà défini comme "note-ascending", on enlève le tri
                if (isset($params['sort']) && $params['sort'] === 'type-ascending') {
                    unset($params['sort']);
                } else {
                    // Ajouter ou mettre à jour le paramètre 'sort'
                    $params['sort'] = 'type-ascending';
                }

                // Construire l'URL avec les nouveaux paramètres
                $urlWithParams = $baseUrl . '?' . http_build_query($params);

                echo htmlspecialchars($urlWithParams);
                ?>" class="flex items-center hover:text-primary duration-100">
                    <p
                        class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-ascending') ? 'border-b border-primary' : ''; ?>">
                        Type d'offre de A à Z</p>
                </a>
                <a href="<?php
                // Base URL sans les paramètres
                $baseUrl = strtok($_SERVER['PHP_SELF'], '?');

                // Initialisation des paramètres
                $params = $_GET;

                // Si le tri est déjà défini comme "note-ascending", on enlève le tri
                if (isset($params['sort']) && $params['sort'] === 'type-descending') {
                    unset($params['sort']);
                } else {
                    // Ajouter ou mettre à jour le paramètre 'sort'
                    $params['sort'] = 'type-descending';
                }

                // Construire l'URL avec les nouveaux paramètres
                $urlWithParams = $baseUrl . '?' . http_build_query($params);

                echo htmlspecialchars($urlWithParams);
                ?>" class="flex items-center hover:text-primary duration-100">
                    <p
                        class="<?php echo (isset($_GET['sort']) && $_GET['sort'] === 'type-descending') ? 'border-b border-primary' : ''; ?>">
                        Type d'offre de Z à A</p>
                </a>
        <?php } ?>
    </div>
</div>

<!-- CHAMPS DE FILTRES TABLETTE -->
<div class="hidden md:hidden border-base200 border-t mr-6 mb-4 w-full" id="filter-section-tab">
    <div class="flex flex-col w-full bg-white p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f1-tab" tabindex="0">
            <p>Catégorie</p>
            <p id="arrow-f1-tab">></p>
        </div>
        <div class="hidden text-sm flex flex-wrap gap-4" id="developped-f1-tab">
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
    <div class="flex flex-col w-full bg-white p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f2-tab" tabindex="0">
            <p>Disponibilité</p>
            <p id="arrow-f2-tab">></p>
        </div>
        <div class="hidden text-sm flex flex-wrap gap-4" id="developped-f2-tab">
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
    <div class="flex flex-col w-full bg-white p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f3-tab" tabindex="0">
            <p>Localisation</p>
            <p id="arrow-f3-tab">></p>
        </div>
        <div class="hidden flex flex-wrap items-center gap-4" id="developped-f3-tab">
            <div class="text-nowrap text-sm flex items-center gap-2 w-full">
                <label>Ville ou Code postal</label>
                <input id="localisation-tab" type="text" class="w-full border border-black p-1 focus:ring-0">
            </div>
        </div>
    </div>
    <div class="flex flex-col w-full bg-white p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f4-tab" tabindex="0">
            <p>Note générale</p>
            <p id="arrow-f4-tab">></p>
        </div>
        <div class="hidden flex items-center" id="developped-f4-tab">
            <label class="text-sm">Intervalle des notes entre&nbsp;</label>
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
    <div class="hidden flex flex-col w-full bg-white p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f5-tab" tabindex="0">
            <p>Période</p>
            <p id="arrow-f5-tab">></p>
        </div>
        <div class="hidden text-sm flex items-center" id="developped-f5-tab">
            <label>Offre allant du&nbsp;</label>
            <input type="date" class="border border-black p-1 text-right mr-4" id="min-date-tab" name="min-date-tab">
            <label>&nbsp;au&nbsp;</label>
            <input type="date" class="border border-black p-1 text-right" id="max-date-tab" name="max-date-tab">
        </div>
    </div>
    <div class="flex flex-col w-full bg-white p-3 gap-4">
        <div class="flex justify-between cursor-pointer" id="button-f6-tab" tabindex="0">
            <p>Prix</p>
            <p id="arrow-f6-tab">></p>
        </div>
        <div class="hidden flex flex-wrap items-center justify-between gap-2" id="developped-f6-tab">
            <div class="flex items-center">
                <label class="text-sm">Intervalle des prix entre&nbsp;</label>
                <input id="min-price-tab" type="number" value="0" min="0" max="99"
                    class="w-[44px] border border-black p-1 text-sm text-right focus:ring-0">
                <label class="text-sm">&nbsp;€&nbsp;et&nbsp;</label>
                <input id="max-price-tab" type="number" value="<?php echo $prix_mini_max; ?>" min="0"
                    max="<?php echo $prix_mini_max; ?>"
                    class="w-[44px] border border-black p-1 text-sm text-right focus:ring-0">
                <label class="text-sm">&nbsp;€</label>
            </div>
            <div class="text-sm flex flex-wrap gap-4">
                <label class="text-sm">Restauration :&nbsp;</label>
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
    <?php if ($pro['data']['type'] === 'prive') { ?>
            <div class="flex flex-col w-full bg-white p-3 gap-4">
                <div class="flex justify-between cursor-pointer" id="button-f7-tab" tabindex="0">
                    <p>Type d'offre</p>
                    <p id="arrow-f7-tab">></p>
                </div>
                <div class="hidden text-sm flex items-center gap-4" id="developped-f7-tab">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="standard-tab">
                        <label for="standard-tab">Standard</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="mb-1" id="premium-tab">
                        <label for="premium-tab">Premium</label>
                    </div>
                </div>
            </div>
    <?php } ?>
</div>