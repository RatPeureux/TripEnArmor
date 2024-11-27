<?php
session_start();
// Enlever les informations gardées lors des étapes de connexion / inscription quand on reveint à la page d'accueil (seul point de sortie de la connexion / inscription)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="/public/images/favicon.png">
    <title>Accueil | PACT</title>

    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/loadComponents.js" defer></script>
    <script type="module" src="/scripts/main.js" defer></script>
</head>

<body class="flex flex-col min-h-screen">

    <div id="header"></div>

    <?php
    // Connexion avec la bdd
    include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    $sort_order = '';
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'price-ascending') {
            $sort_order = 'ORDER BY prix_mini ASC';
        } elseif ($_GET['sort'] == 'price-descending') {
            $sort_order = 'ORDER BY prix_mini DESC';
        }
    }

    // Obtenez l'ensemble des offres avec le tri approprié
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true $sort_order");
    $stmt->execute();
    $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- MAIN (TABLETTE et TÉLÉPHONE -->
    <div class="w-full flex justify-center p-2">
        <div class="flex justify-center md:max-w-[1280px]">
            <div id="menu" class="2"></div>

            <main class="grow p-4 md:p-2 flex flex-col md:mx-10 md:self-center md:rounded-lg">

                <!-- BOUTONS DE FILTRES ET DE TRIS TABLETTE -->
                <div class="flex justify-between items-end mb-2">
                    <h1 class="text-4xl">Toutes les offres</h1>

                    <div class="hidden md:flex gap-4">
                        <a href="#" class="flex items-center gap-2 hover:text-primary duration-100" id="filter-button-tab">
                            <i class="text xl fa-solid fa-filter"></i>
                            <p>Filtrer</p>
                        </a>
                        |
                        <a href="#" class="self-end flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tab">
                            <i class="text xl fa-solid fa-sort"></i>
                            <p>Trier par</p>
                        </a>
                    </div>
                </div>

                <!-- DROPDOWN MENU TRIS TABLETTE-->
                <div class="hidden md:hidden relative" id="sort-section-tab">
                    <div class="absolute top-0 right-0 z-20 self-end bg-white border border-black p-2 max-w-48 flex flex-col gap-4">
                        <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Note croissante</p>
                        </a>
                        <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Note décroissante</p>
                        </a>
                        <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Prix croissant</p>
                        </a>
                        <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Prix décroissant</p>
                        </a>
                    </div>
                </div>

                <!-- CHAMPS DE FILTRES TABLETTE -->
                <div class="hidden md:hidden space-y-4 mr-6 mb-4 w-full" id="filter-section-tab">
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f1-tab">
                            <p>Catégorie</p>
                            <p id="arrow-f1-tab">></p>
                        </div>
                        <div class="hidden flex flex-wrap gap-4" id="developped-f1-tab">
                            <div class="flex gap-2">
                                <input type="checkbox" id="restauration" name="restauration" />
                                <label for="restauration">Restauration (x)</label>
                            </div>

                            <div class="flex gap-2">
                                <input type="checkbox" id="activite" name="activite" />
                                <label for="activite">Activité (x)</label>
                            </div>

                            <div class="flex gap-2">
                                <input type="checkbox" id="spectacle" name="spectacle" />
                                <label for="spectacle">Spectacle (x)</label>
                            </div>

                            <div class="flex gap-2">
                                <input type="checkbox" id="visite" name="visite" />
                                <label for="visite">Visite (x)</label>
                            </div>

                            <div class="flex gap-2">
                                <input type="checkbox" id="parc_attraction" name="parc_attraction" />
                                <label for="parc_attraction">Parc d'attraction (x)</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f2-tab">
                            <p>Disponibilité</p>
                            <p id="arrow-f2-tab">></p>
                        </div>
                        <div class="hidden flex flex-wrap gap-4" id="developped-f2-tab">
                            <div class="flex gap-2">
                                <input type="checkbox" id="open" name="open" />
                                <label for="open">Ouvert (x)</label>
                            </div>

                            <div class="flex gap-2">
                                <input type="checkbox" id="closes" name="closes" />
                                <label for="closes">Fermé (x)</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f3-tab">
                            <p>Localisation</p>
                            <p id="arrow-f3-tab">></p>
                        </div>
                        <div class="hidden flex flex-wrap items-center gap-4" id="developped-f3-tab">
                            <div class="flex items-center gap-2">
                                <label class="text-small" for="code">Code postal</label>
                                <input id="code" type="text" class="w-16 border border-[#999999] rounded-lg p-1 focus:ring-0" />
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-small" for="code">Ville</label>
                                <input id="code" type="text" class="max-w-full border border-[#999999] rounded-lg p-1 focus:ring-0" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f4-tab">
                            <p>Note générale</p>
                            <p id="arrow-f4-tab">></p>
                        </div>
                        <div class="hidden flex flex-col w-full" id="developped-f4-tab">
                            <div class="relative">
                                <input id="from-slider-note-tab" type="range" value="0" min="0" max="5" step="0.5" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                                <input id="to-slider-note-tab" type="range" value="5" min="0" max="5" step="0.5" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                                <div id="range-background1" class="absolute top-0 left-0"></div>
                            </div>

                            <div class="relative flex justify-between mt-3">
                                <div class="flex items-start">
                                    <input id="from-input-note-tab" type="number" value="0" min="0" max="5" step="0.5" class="w-[39px] focus:ring-0" />
                                    <img src="/public/icones/egg-full.svg" width="10" class="mt-1">
                                </div>
                                <div class="flex items-start">
                                    <input id="to-input-note-tab" type="number" value="5" min="0" max="5" step="0.5" class="w-[39px] focus:ring-0" />
                                    <img src="/public/icones/egg-full.svg" width="10" class="mt-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f5-tab">
                            <p>Prix</p>
                            <p id="arrow-f5-tab">></p>
                        </div>
                        <div class="hidden flex flex-wrap" id="developped-f5-tab">
                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <input id="from-slider-price-tab" type="range" value="0" min="0" max="99" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                                    <input id="to-slider-price-tab" type="range" value="99" min="0" max="99" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                                    <div id="range-background-price-tab" class="absolute top-0 left-0"></div>
                                </div>

                                <div class="relative flex justify-between mt-3">
                                    <div class="flex items-center">
                                        <input id="from-input-price-tab" type="number" value="0" min="0" max="99" class="w-[34px] focus:ring-0" />
                                        €
                                    </div>
                                    <div class="flex items-center">
                                        <input id="to-input-price-tab" type="number" value="99" min="0" max="99" class="w-[34px] focus:ring-0" />
                                        €
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <?php
                    // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
                    if (!$toutesLesOffres) {
                        echo "<p class='font-bold'>Il n'existe aucune offre...</p>";
                    } else {
                        $i = 0;
                        foreach ($toutesLesOffres as $offre) {
                            if ($i < 3) {
                                // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                                $mode_carte = 'membre';
                                include dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre.php';
                                $i++;
                            }
                        }
                    }
                    ?>
                </div>
            </main>
        </div>

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
                <div class="hidden md:hidden absolute bottom-[72px] right-2 z-20 bg-white border border-black p-2 max-w-48 flex flex-col gap-4" id="sort-section-tel">
                    <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Note croissante</p>
                    </a>
                    <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Note décroissante</p>
                    </a>
                    <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Prix croissant</p>
                    </a>
                    <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Prix décroissant</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div id="footer"></div>

    <!-- MENU FILTRE TÉLÉPHONE -->
    <div class="block md:hidden flex flex-col justify-between absolute w-full h-full bg-base100 -translate-x-full duration-200 z-50" id="filtres">
        <div>
            <div class="p-4 gap-4 flex justify-start items-center h-20 border-b-2 border-black">
                <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleFiltres()"></i>
                <h1 class="text-h1">Filtres</h1>
            </div>

            <div class="w-full">
                <div class="flex flex-col w-full border-b-2 border-black pl-5 p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f1-tel">
                        <p>Catégorie</p>
                        <p id="arrow-f1-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-wrap gap-4" id="developped-f1-tel">
                        <div class="flex gap-2">
                            <input type="checkbox" id="restauration" name="restauration" />
                            <label for="restauration">Restauration (x)</label>
                        </div>

                        <div class="flex gap-2">
                            <input type="checkbox" id="activite" name="activite" />
                            <label for="activite">Activité (x)</label>
                        </div>

                        <div class="flex gap-2">
                            <input type="checkbox" id="spectacle" name="spectacle" />
                            <label for="spectacle">Spectacle (x)</label>
                        </div>

                        <div class="flex gap-2">
                            <input type="checkbox" id="visite" name="visite" />
                            <label for="visite">Visite (x)</label>
                        </div>

                        <div class="flex gap-2">
                            <input type="checkbox" id="parc_attraction" name="parc_attraction" />
                            <label for="parc_attraction">Parc d'attraction (x)</label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black pl-5 p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f2-tel">
                        <p>Disponibilité</p>
                        <p id="arrow-f2-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-wrap gap-4" id="developped-f2-tel">
                        <div class="flex gap-2">
                            <input type="checkbox" id="open" name="open" />
                            <label for="open">Ouvert (x)</label>
                        </div>

                        <div class="flex gap-2">
                            <input type="checkbox" id="closes" name="closes" />
                            <label for="closes">Fermé (x)</label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black pl-5 p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f3-tel">
                        <p>Localisation</p>
                        <p id="arrow-f3-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-nowrap w-full items-center gap-4" id="developped-f3-tel">
                        <div class="flex items-center gap-2">
                            <label class="text-small" for="code">Code postal</label>
                            <input id="code" type="text" class="w-16 bg-base100 border border-[#999999] rounded-lg p-1 focus:ring-0" />
                        </div>

                        <div class="flex items-center gap-2 w-full">
                            <label class="text-small" for="code">Ville</label>
                            <input id="code" type="text" class="w-full bg-base100 border border-[#999999] rounded-lg p-1 focus:ring-0" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black pl-5 p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f4-tel">
                        <p>Note générale</p>
                        <p id="arrow-f4-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-col w-full" id="developped-f4-tel">
                        <div class="relative">
                            <input id="from-slider-note-tel" type="range" value="0" min="0" max="5" step="0.5" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                            <input id="to-slider-note-tel" type="range" value="5" min="0" max="5" step="0.5" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                            <div id="range-background-note-tel" class="absolute top-0 left-0"></div>
                        </div>

                        <div class="relative flex justify-between mt-3">
                            <div class="flex items-start">
                                <input class="bg-base100" id="from-input-note-tel" type="number" value="0" min="0" max="5" step="0.5" class="w-[39px] focus:ring-0" />
                                <img src="/public/icones/egg-full.svg" width="10" class="mt-1">
                            </div>
                            <div class="flex items-start">
                                <input class="bg-base100" id="to-input-note-tel" type="number" value="5" min="0" max="5" step="0.5" class="w-[39px] focus:ring-0" />
                                <img src="/public/icones/egg-full.svg" width="10" class="mt-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black pl-5 p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f5-tel">
                        <p>Prix</p>
                        <p id="arrow-f5-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-wrap" id="developped-f5-tel">
                        <div class="flex flex-col w-full">
                            <div class="relative">
                                <input id="from-slider-price-tel" type="range" value="0" min="0" max="99" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                                <input id="to-slider-price-tel" type="range" value="99" min="0" max="99" class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                                <div id="range-background-price-tel" class="absolute top-0 left-0"></div>
                            </div>

                            <div class="relative flex justify-between mt-3">
                                <div class="flex items-center">
                                    <input class="bg-base100" id="from-input-price-tel" type="number" value="0" min="0" max="99" class="w-[34px] focus:ring-0" />
                                    €
                                </div>
                                <div class="flex items-center">
                                    <input class="bg-base100" id="to-input-price-tel" type="number" value="99" min="0" max="99" class="w-[34px] focus:ring-0" />
                                    €
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="uppercase bg-primary font-bold text-white text-center m-2 p-4" onclick="toggleFiltres()">
            Voir les offres
        </a>
    </div>

</body>

</html>

<script>
    // Fonction pour configurer un bouton qui affiche ou masque une section
    function setupToggleTab(buttonId, sectionId) {
        const button = document.getElementById(buttonId); // Récupère le bouton par son ID
        const section = document.getElementById(sectionId); // Récupère la section par son ID

        if (button && section) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) { // Ajoute un événement au clic
                event.preventDefault(); // Empêche le comportement par défaut (ex: navigation)
                // Alterne entre affichage (md:block) et masquage (md:hidden) de la section
                if (section.classList.contains('md:hidden')) {
                    section.classList.remove('md:hidden');
                    section.classList.add('md:block');
                } else {
                    section.classList.remove('md:block');
                    section.classList.add('md:hidden');
                }
            });
        }
    }

    // Initialisation des boutons pour les onglets (ex: filtres et tri)
    setupToggleTab('filter-button-tab', 'filter-section-tab');
    setupToggleTab('sort-button-tab', 'sort-section-tab');

    // Fonction pour configurer un bouton qui masque ou affiche une section en mode téléphone
    function setupToggleTel(buttonId, sectionId) {
        const button = document.getElementById(buttonId); // Récupère le bouton
        const section = document.getElementById(sectionId); // Récupère la section

        if (button && section) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut
                section.classList.toggle('hidden'); // Alterne la classe 'hidden'
            });
        }
    }

    // Initialisation des boutons pour téléphone (ex: filtres et tri)
    setupToggleTel('filter-button-tel', 'filter-section-tel');
    setupToggleTel('sort-button-tel', 'sort-section-tel');

    // Fonction pour gérer les filtres avec flèches et contenus développables
    function developpedFilter(buttonId, arrowId, developpedId) {
        const button = document.getElementById(buttonId); // Récupère le bouton
        const arrow = document.getElementById(arrowId); // Récupère l'icône flèche
        const developped = document.getElementById(developpedId); // Récupère la section développable

        if (button && arrow && developped) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut
                arrow.classList.toggle('rotate-90'); // Alterne la rotation de l'icône
                developped.classList.toggle('hidden'); // Alterne la visibilité de la section
            });
        }
    }

    // Fonction pour gérer les filtres avec flèches et contenus développables (referme les autres filtres ouverts)
    function developpedFilterAutoClose(buttonId, arrowId, developpedId) {
        const button = document.getElementById(buttonId); // Récupère le bouton
        const arrow = document.getElementById(arrowId); // Récupère l'icône flèche
        const developped = document.getElementById(developpedId); // Récupère la section développable

        if (button && arrow && developped) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut

                // Ferme toutes les autres sections développables
                const allDevelopped = document.querySelectorAll('.developped'); // Sélectionne toutes les sections développables
                const allArrows = document.querySelectorAll('.arrow'); // Sélectionne toutes les icônes flèches

                allDevelopped.forEach(section => {
                    if (section !== developped) {
                        section.classList.add('hidden'); // Cache toutes les autres sections
                    }
                });

                allArrows.forEach(icon => {
                    if (icon !== arrow) {
                        icon.classList.remove('rotate-90'); // Réinitialise la rotation des autres icônes
                    }
                });

                // Alterne l'état de la section cliquée
                arrow.classList.toggle('rotate-90'); // Alterne la rotation de l'icône de la section actuelle
                developped.classList.toggle('hidden'); // Alterne la visibilité de la section actuelle
            });
        }
    }

    // Initialisation des filtres pour les onglets (tablette et bureau)
    developpedFilter('button-f1-tab', 'arrow-f1-tab', 'developped-f1-tab');
    developpedFilter('button-f2-tab', 'arrow-f2-tab', 'developped-f2-tab');
    developpedFilter('button-f3-tab', 'arrow-f3-tab', 'developped-f3-tab');
    developpedFilter('button-f4-tab', 'arrow-f4-tab', 'developped-f4-tab');
    developpedFilter('button-f5-tab', 'arrow-f5-tab', 'developped-f5-tab');

    // Initialisation des filtres pour téléphone
    developpedFilterAutoClose('button-f1-tel', 'arrow-f1-tel', 'developped-f1-tel');
    developpedFilterAutoClose('button-f2-tel', 'arrow-f2-tel', 'developped-f2-tel');
    developpedFilterAutoClose('button-f3-tel', 'arrow-f3-tel', 'developped-f3-tel');
    developpedFilterAutoClose('button-f4-tel', 'arrow-f4-tel', 'developped-f4-tel');
    developpedFilterAutoClose('button-f5-tel', 'arrow-f5-tel', 'developped-f5-tel');

    // Fonction pour mettre à jour la valeur affichée par un slider
    function updateSliderValue(value) {
        document.getElementById('slider-value').textContent = parseFloat(value).toFixed(1); // Affiche la valeur formatée
    }

    // Contrôle de la position du curseur "from" en fonction de la valeur
    function controlFromInput(fromSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput); // Récupère les valeurs numériques
        fillSlider(fromInput, toInput, '#cccccc', '#0a77ec', controlSlider); // Met à jour l'apparence du slider

        if (from > to) { // Empêche que "from" dépasse "to"
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromSlider.value = from;
        }
    }

    // Contrôle de la position du curseur "to"
    function controlToInput(toSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#cccccc', '#0a77ec', controlSlider);
        setToggleAccessible(toInput); // Met à jour l'accessibilité visuelle

        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
        }
    }

    // Gère les changements du slider "from"
    function controlFromSlider(fromSlider, toSlider, fromInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#cccccc', '#0a77ec', toSlider);

        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromInput.value = from;
        }
    }

    // Gère les changements du slider "to"
    function controlToSlider(fromSlider, toSlider, toInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#cccccc', '#0a77ec', toSlider);
        setToggleAccessible(toSlider);

        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
            toSlider.value = from;
        }
    }

    // Parse les valeurs des sliders pour les convertir en nombres
    function getParsed(currentFrom, currentTo) {
        const from = parseFloat(currentFrom.value);
        const to = parseFloat(currentTo.value);

        return [from, to];
    }

    // Met à jour l'apparence du slider avec un dégradé
    function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
        const rangeDistance = to.max - to.min;
        const fromPosition = from.value - to.min;
        const toPosition = to.value - to.min;

        controlSlider.style.background = `linear-gradient(
            to right,
            ${sliderColor} 0%,
            ${sliderColor} ${(fromPosition) / (rangeDistance) * 100}%,
            ${rangeColor} ${(fromPosition) / (rangeDistance) * 100}%,
            ${rangeColor} ${(toPosition) / (rangeDistance) * 100}%, 
            ${sliderColor} ${(toPosition) / (rangeDistance) * 100}%, 
            ${sliderColor} 100%)`;
    }

    // Met à jour l'accessibilité en fonction de la valeur du slider
    function setToggleAccessible(currentTarget) {
        if (!currentTarget) return;
        const toSlider = currentTarget;
        if (Number(currentTarget.value) <= 0) {
            toSlider.classList.add('z-2'); // Ajoute une classe spécifique si nécessaire
        } else {
            toSlider.classList.remove('z-2');
        }
    }

    // Initialise les sliders avec leurs entrées associées
    function initializeSliderControls(sliderFromId, sliderToId, inputFromId, inputToId) {
        const fromSlider = document.querySelector(sliderFromId);
        const toSlider = document.querySelector(sliderToId);
        const fromInput = document.querySelector(inputFromId);
        const toInput = document.querySelector(inputToId);

        if (fromSlider && toSlider && fromInput && toInput) {
            fillSlider(fromSlider, toSlider, '#cccccc', '#0a77ec', toSlider);
            setToggleAccessible(toSlider);

            // Ajoute les événements pour synchroniser sliders et inputs
            fromSlider.oninput = () => controlFromSlider(fromSlider, toSlider, fromInput);
            toSlider.oninput = () => controlToSlider(fromSlider, toSlider, toInput);
            fromInput.oninput = () => controlFromInput(fromSlider, fromInput, toInput, toSlider);
            toInput.oninput = () => controlToInput(toSlider, fromInput, toInput, toSlider);
        } else {
            console.error(`Error initializing sliders: Check element IDs (${sliderFromId}, ${sliderToId}, ${inputFromId}, ${inputToId}).`);
        }
    }

    // Initialisation des sliders pour les différents critères (note et prix, par onglet et téléphone)
    initializeSliderControls('#from-slider-note-tab', '#to-slider-note-tab', '#from-input-note-tab', '#to-input-note-tab');
    initializeSliderControls('#from-slider-price-tab', '#to-slider-price-tab', '#from-input-price-tab', '#to-input-price-tab');

    initializeSliderControls('#from-slider-note-tel', '#to-slider-note-tel', '#from-input-note-tel', '#to-input-note-tel');
    initializeSliderControls('#from-slider-price-tel', '#to-slider-price-tel', '#from-input-price-tel', '#to-input-price-tel');

    // Fonction pour afficher ou masquer un conteneur de filtres
    function toggleFiltres() {
        let filtres = document.querySelector('#filtres');

        if (filtres) {
            filtres.classList.toggle('active'); // Alterne la classe 'active'
        }
    }
</script>