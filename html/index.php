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

    <div id="header" class="mb-10"></div>

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
    // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
    if (!$toutesLesOffres) {
        echo "<p class='font-bold'>Il n'existe aucune offre...</p>";
    } else {
        $allCardsTextPhone = '';
        $allCardsTextTablette = '';
        $i = 0;
        foreach ($toutesLesOffres as $offre) {
            if ($i < 1) {
                // Avoir une variable $pro qui contient les informations du pro actuel.
                $id_pro = $offre['id_pro'];
                $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE id_compte = :id_pro");
                $stmt->bindParam(':id_pro', $id_pro);
                $stmt->execute();
                $pro = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($pro) {
                    $pro_nom = $pro['nom_pro'];
                }

                // Obtenir les différentes variables avec les infos nécessaires via des requêtes SQL sécurisées (bindParams)
                include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/get_details_offre.php';

                // Ajouter le contenu des cartes pour le téléphone
                {
                    $allCardsTextPhone .= "<a href='/scripts/go_to_details.php?id_offre=$id_offre'>
                            <div class='card";
                    // Afficher en exergue si la carte a une option (à la une ou en relief)
                    if ($option) {
                        $allCardsTextPhone .= ' active';
                    }
                    $allCardsTextPhone .= " relative bg-base200 rounded-xl flex flex-col'>
                                    <!-- En tête -->
                                    <div
                                        class='en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg'>
                                        <h3 class='text-center font-bold'>$titre_offre</h3>
                                    <div class='flex w-full justify-between px-2'>
                                        <p class='text-small'>$pro_nom</p>
                                        <p class='text-small'>$categorie_offre</p>
                                    </div>
                                </div>
                                <!-- Image de fond -->
                                <img class='h-48 w-full rounded-t-lg object-cover' src='/public/images/<?php echo ($id_type_offre == 1) ? 'restauration' : ($id_type_offre == 2) ? 'activite' : ($id_type_offre == 3) ? 'spectacle' : ($id_type_offre == 4) ? 'visite' : ($id_type_offre == 5) ? 'parc_attraction' : 'ex' ?>.jpg'
                                    alt='Image promotionnelle de l'offre'>
                                <!-- Infos principales -->
                                <div class='infos flex items-center justify-around gap-2 px-2 grow'>
                                    <!-- Localisation -->
                                    <div class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center'>
                                        <i class='fa-solid fa-location-dot'></i>
                                        <p class='text-small'>$ville</p>
                                        <p class='text-small'>$code_postal</p>
                                    </div>
                                    <hr class='h-20 border-black border'>
                                    <!-- Description -->
                                    <div class='description py-2 flex flex-col gap-2 justify-center self-stretch'>
                                        <div class='p-1 rounded-lg bg-secondary self-center'>
                                            <p class='text-white text-center text-small font-bold'>";
                    // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                    if ($tags) {
                        $allCardsTextPhone .= $tags;
                    } else {
                        $allCardsTextPhone .= 'Aucun tag';
                    }
                    $allCardsTextPhone .= "</p>
                                        </div>
                                        <p class='overflow-hidden line-clamp-2 text-small'>
                                            $resume
                                        </p>
                                    </div>
                                    <hr class='h-20 border-black border'>
                                    <!-- Notation et Prix -->
                                    <div class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                                        <p class='text-small'>$prix_a_afficher</p>
                                    </div>
                                </div>
                            </div>
                        </a>";
                }

                // Afficher le contenu des cartes pour la tablette
                {
                    $allCardsTextTablette .= "
                    <a href='/scripts/go_to_details.php?id_offre=$id_offre'>
                                <div class='card";
                    // Afficher en exergue si la carte a une option (à la une ou en relief)
                    if ($option) {
                        $allCardsTextTablette .= ' active';
                    }
                    $allCardsTextTablette .= " relative bg-base200 rounded-lg flex'>
                                        <!-- Partie gauche -->
                                        <div class='gauche grow relative shrink-0 basis-1/2 h-[280px] overflow-hidden'>
                                            <!-- En tête -->
                                            <div
                                                class='en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg'>
                                                <h3 class='text-center font-bold'>$titre_offre</h3>
                                            <div class='flex w-full justify-between px-2'>
                                                <p class='text-small'>$pro_nom</p>
                                                <p class='text-small'>$categorie_offre</p>
                                            </div>
                                        </div>
                                        <!-- Image de fond -->
                                        <img class='rounded-l-lg w-full h-full object-cover object-center'
                                            src='/public/images/<?php echo ($id_type_offre == 1) ? 'restauration' : ($id_type_offre == 2) ? 'activite' : ($id_type_offre == 3) ? 'spectacle' : ($id_type_offre == 4) ? 'visite' : ($id_type_offre == 5) ? 'parc_attraction' : 'ex' ?>.jpg' alt='Image promotionnelle de l'offre'>
                                    </div>
                                    <!-- Partie droite (infos principales) -->
                                    <div class='infos flex flex-col items-center basis-1/2 p-3 gap-3 justify-between'>
                                        <!-- Description -->
                                        <div class='description py-2 flex flex-col gap-2'>
                                            <div class='p-1 rounded-lg bg-secondary self-center'>
                                                <p class='text-white text-center text-small font-bold'>";
                    // Afficher les tags / plats de l'offre, sinon mentionner l'absence de ces derniers
                    if ($tags) {
                        $allCardsTextTablette .= $tags;
                    } else {
                        $allCardsTextTablette .= 'Aucun tag';
                    }
                    $allCardsTextTablette .= "</p>
                                            </div>
                                            <p class='overflow-hidden line-clamp-5 text-small'>
                                                $resume
                                            </p>
                                        </div>
                                        <!-- A droite, en bas -->
                                        <div class='self-stretch flex flex-col gap-2'>
                                            <hr class='border-black w-full'>
                                            <div class='flex justify-around self-stretch'>
                                                <!-- Localisation -->
                                                <div
                                                    class='localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center'>
                                                    <i class='fa-solid fa-location-dot'></i>
                                                    <p class='text-small'>$ville</p>
                                                    <p class='text-small'>$code_postal</p>
                                                </div>
                                                <!-- Notation et Prix -->
                                                <div
                                                    class='localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center'>
                                                    <p class='text-small'>$prix_a_afficher</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                    ";
                }
            }
            $i++;
        }
    }
    ?>

    <!-- VERSION TELEPHONE -->
    <main class="phone md:hidden grow p-4 flex flex-col gap-4">
        <div id="menu" class="2"></div>

        <h1 class="text-4xl">Toutes les offres</h1>

        <!--
        ### CARD COMPONENT ! ###
        Composant dynamique (généré avec les données en php)
        Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
        -->
        <?php
        if (!$toutesLesOffres) {
            echo "<p class='font-bold'>Vous n'avez aucune offre...</p>";
        } else {
            print_r($allCardsTextPhone);
        }
        ?>
    </main>

    <div class="block md:hidden p-4 h-16 w-full bg-bgBlur/75 backdrop-blur border-t-2 border-black fixed bottom-0 flex items-center justify-between">
        <a href="#" class="p-2 flex items-center gap-2 hover:text-primary duration-100">
            <i class="text xl fa-solid fa-filter"></i>
            <p>Filtrer</p>
        </a>

        <a href="#" class="p-2 flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tel">
            <i class="text xl fa-solid fa-sort"></i>
            <p>Trier par</p>
        </a>
    </div>

    <div class="hidden md:hidden bg-white fixed bottom-[4.5rem] right-2 border border-black p-2 w-44 flex flex-col" id="sort-section-tel">
        <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note croissante</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Note décroissante</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>" class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix croissant</p>
        </a>
        &nbsp;
        &nbsp;
        <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>" class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
            <p>Prix décroissant</p>
        </a>
    </div>

    <!-- VERSION TABLETTE -->
    <main class="hidden md:block grow mx-10 self-center rounded-lg p-2 max-w-[1280px]">
        <div class="flex gap-3">
            <!-- PARTIE GAUCHE (menu) -->
            <div class="sticky flex flex-col gap-4">
                <div id="menu" class="2"></div>
            </div>

            <!-- PARTIE DROITE (offres & leurs détails) -->
            <div class="tablette p-4 flex flex-col gap-4">

                <div class="flex justify-between items-end">
                    <h1 class="text-4xl">Toutes les offres</h1>

                    <a href="#" class="flex items-center gap-2 hover:text-primary duration-100" id="filter-button-tab">
                        <i class="text xl fa-solid fa-filter"></i>
                        <p>Filtrer</p>
                    </a>
                </div>

                <div class="hidden space-y-4 mr-6 w-full" id="filter-section-tab">
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f1">
                            <p>Catégorie</p>
                            <p id="arrow-f1">></p>
                        </div>
                        <div class="hidden flex flex-wrap gap-4" id="developped-f1">
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
                        <div class="flex justify-between cursor-pointer" id="button-f2">
                            <p>Disponibilité</p>
                            <p id="arrow-f2">></p>
                        </div>
                        <div class="hidden flex flex-wrap gap-4" id="developped-f2">
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
                        <div class="flex justify-between cursor-pointer" id="button-f3">
                            <p>Localisation</p>
                            <p id="arrow-f3">></p>
                        </div>
                        <div class="hidden flex flex-wrap" id="developped-f3">

                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f4">
                            <p>Note générale</p>
                            <p id="arrow-f4">></p>
                        </div>
                        <div class="hidden flex flex-wrap" id="developped-f4">
                            <div class="flex items-center space-x-4">
                                <input type="range" id="note-slider" min="0" max="5" step="0.5" value="2.5" class="w-64 h-2 bg-gray-200 rounded-lg appearance-none focus:outline-none" oninput="updateSliderValue(this.value)">
                                <span id="slider-value" class="font-bold">3</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black pl-5 p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f5">
                            <p>Prix</p>
                            <p id="arrow-f5">></p>
                        </div>
                        <div class="hidden flex flex-wrap" id="developped-f5">
                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <input id="fromSlider" type="range" value="25" min="0" max="99" class="absolute w-full h-2 bg-gray-200 rounded-lg  appearance-none pointer-events-auto z-1" />
                                    <input id="toSlider" type="range" value="80" min="0" max="99" class="absolute w-full h-2 bg-gray-200 rounded-lg  appearance-none pointer-events-auto z-2" />

                                    <div id="rangeBackground" class="absolute top-0 left-0"></div>
                                </div>

                                <div class="relative flex justify-between mt-3">
                                    <div class="flex items-center">
                                        <input id="fromInput" type="number" value="25" min="0" max="99" class="w-[34px] focus:ring-0" />
                                        €
                                    </div>
                                    <div class="flex items-center">
                                        <input id="toInput" type="number" value="80" min="0" max="99" class="w-[34px] focus:ring-0" />
                                        €
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="#" class="self-end flex items-center gap-2 hover:text-primary duration-100" id="sort-button-tab">
                    <i class="text xl fa-solid fa-sort"></i>
                    <p>Trier par</p>
                </a>

                <div class="hidden relative" id="sort-section-tab">
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

                <!--
                ### CARD COMPONENT ! ###
                Composant dynamique (généré avec les données en php)
                Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
                -->
                <?php
                if (!$toutesLesOffres) {
                    echo "<p class='font-bold'>Vous n'avez aucune offre...</p>";
                } else {
                    print_r($allCardsTextTablette);
                }
                ?>
            </div>
        </div>
    </main>

    <div id="footer"></div>
</body>

</html>


<script>
    function setupToggle(buttonId, sectionId) {
        const button = document.getElementById(buttonId);
        const section = document.getElementById(sectionId);

        if (button && section) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                section.classList.toggle('hidden');
            });
        }
    }

    setupToggle('sort-button-tel', 'sort-section-tel');
    setupToggle('sort-button-tab', 'sort-section-tab');

    setupToggle('filter-button-tel', 'filter-section-tel');
    setupToggle('filter-button-tab', 'filter-section-tab');

    function developpedFilter(buttonId, arrowId, developpedId) {
        const button = document.getElementById(buttonId);
        const arrow = document.getElementById(arrowId);
        const developped = document.getElementById(developpedId);

        if (button && arrow && developped) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                arrow.classList.toggle('rotate-90');
                developped.classList.toggle('hidden');
            });
        }
    }

    developpedFilter('button-f1', 'arrow-f1', 'developped-f1');
    developpedFilter('button-f2', 'arrow-f2', 'developped-f2');
    developpedFilter('button-f3', 'arrow-f3', 'developped-f3');
    developpedFilter('button-f4', 'arrow-f4', 'developped-f4');
    developpedFilter('button-f5', 'arrow-f5', 'developped-f5');

    function updateSliderValue(value) {
        document.getElementById('slider-value').textContent = parseFloat(value).toFixed(1);
    }

    function controlFromInput(fromSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#C6C6C6', '#0a77ec', controlSlider);
        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromSlider.value = from;
        }
    }
        
    function controlToInput(toSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#C6C6C6', '#0a77ec', controlSlider);
        setToggleAccessible(toInput);
        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
        }
    }

    function controlFromSlider(fromSlider, toSlider, fromInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#0a77ec', toSlider);
        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromInput.value = from;
        }
    }

    function controlToSlider(fromSlider, toSlider, toInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#0a77ec', toSlider);
        setToggleAccessible(toSlider);
        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
            toSlider.value = from;
        }
    }

    function getParsed(currentFrom, currentTo) {
        const from = parseInt(currentFrom.value, 10);
        const to = parseInt(currentTo.value, 10);
        return [from, to];
    }

    function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
        const rangeDistance = to.max-to.min;
        const fromPosition = from.value - to.min;
        const toPosition = to.value - to.min;
        controlSlider.style.background = `linear-gradient(
        to right,
        ${sliderColor} 0%,
        ${sliderColor} ${(fromPosition)/(rangeDistance)*100}%,
        ${rangeColor} ${((fromPosition)/(rangeDistance))*100}%,
        ${rangeColor} ${(toPosition)/(rangeDistance)*100}%, 
        ${sliderColor} ${(toPosition)/(rangeDistance)*100}%, 
        ${sliderColor} 100%)`;
    }

    function setToggleAccessible(currentTarget) {
        const toSlider = document.querySelector('#toSlider');
        if (Number(currentTarget.value) <= 0) {
            toSlider.classList.toggle('z-2');
        } else {
            toSlider.classList.toggle('z-2');
        }
    }

    const fromSlider = document.querySelector('#fromSlider');
    const toSlider = document.querySelector('#toSlider');
    const fromInput = document.querySelector('#fromInput');
    const toInput = document.querySelector('#toInput');
    fillSlider(fromSlider, toSlider, '#C6C6C6', '#0a77ec', toSlider);
    setToggleAccessible(toSlider);

    fromSlider.oninput = () => controlFromSlider(fromSlider, toSlider, fromInput);
    toSlider.oninput = () => controlToSlider(fromSlider, toSlider, toInput);
    fromInput.oninput = () => controlFromInput(fromSlider, fromInput, toInput, toSlider);
    toInput.oninput = () => controlToInput(toSlider, fromInput, toInput, toSlider);
</script>