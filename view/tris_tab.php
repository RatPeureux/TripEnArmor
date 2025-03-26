<!-- DROPDOWN MENU TRIS TABLETTE-->
<div class="hidden md:hidden relative z-50" id="sort-section-tab">
    <div class="absolute top-0 right-0 self-end bg-white border border-black shadow-md max-w-48 p-2 flex flex-col gap-4">
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