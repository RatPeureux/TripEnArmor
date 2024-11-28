<?php
session_start();
// Enlever les informations gardées lors des étapes de connexion / inscription quand on reveint à la page d'accueil (seul point de sortie de la connexion / inscription)
unset($_SESSION['data_en_cours_connexion']);
unset($_SESSION['data_en_cours_inscription']);

include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
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

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header.php';
    ?>

    <?php
    // Connexion avec la bdd
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

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
                        <a href="#" class="flex items-center gap-2 hover:text-primary duration-100"
                            id="filter-button-tab">
                            <i class="text xl fa-solid fa-filter"></i>
                            <p>Filtrer</p>
                        </a>
                        |
                        <a href="#" class="self-end flex items-center gap-2 hover:text-primary duration-100"
                            id="sort-button-tab">
                            <i class="text xl fa-solid fa-sort"></i>
                            <p>Trier par</p>
                        </a>
                    </div>
                </div>

                <!-- DROPDOWN MENU TRIS TABLETTE-->
                <div class="hidden md:hidden relative" id="sort-section-tab">
                    <div
                        class="absolute top-0 right-0 z-20 self-end bg-white border border-black p-2 max-w-48 flex flex-col gap-4">
                        <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>"
                            class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Note croissante</p>
                        </a>
                        <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>"
                            class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Note décroissante</p>
                        </a>
                        <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>"
                            class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Prix croissant</p>
                        </a>
                        <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>"
                            class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                            <p>Prix décroissant</p>
                        </a>
                    </div>
                </div>

                <!-- CHAMPS DE FILTRES TABLETTE -->
                <div class="hidden md:hidden space-y-4 mr-6 mb-4 w-full" id="filter-section-tab">
                    <div class="flex flex-col w-full border border-black p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f1-tab">
                            <p>Catégorie</p>
                            <p id="arrow-f1-tab">></p>
                        </div>
                        <div class="hidden text-small flex flex-wrap gap-4" id="developped-f1-tab">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="restauration" name="restauration" />
                                <label for="restauration">Restauration</label>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="activite" name="activite" />
                                <label for="activite">Activité</label>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="spectacle" name="spectacle" />
                                <label for="spectacle">Spectacle</label>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="visite" name="visite" />
                                <label for="visite">Visite</label>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="parc_attraction" name="parc_attraction" />
                                <label for="parc_attraction">Parc d'attraction</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f2-tab">
                            <p>Disponibilité</p>
                            <p id="arrow-f2-tab">></p>
                        </div>
                        <div class="hidden text-small flex flex-wrap gap-4" id="developped-f2-tab">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="open" name="open" />
                                <label for="open">Ouvert</label>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="mb-1" id="closes" name="closes" />
                                <label for="closes">Fermé</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f3-tab">
                            <p>Localisation</p>
                            <p id="arrow-f3-tab">></p>
                        </div>
                        <div class="hidden flex flex-wrap items-center gap-4" id="developped-f3-tab">
                            <div class="text-nowrap text-small flex items-center gap-2 w-full">
                                <label for="loc">Code postal</label>
                                <label class="text-[#999999]" for="loc">ou</label>
                                <label for="loc">Ville</label>
                                <input id="loc" type="text"
                                    class="w-full border border-[#999999] rounded-lg p-1 focus:ring-0" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f4-tab">
                            <p>Note générale</p>
                            <p id="arrow-f4-tab">></p>
                        </div>
                        <div class="hidden flex flex-col w-full" id="developped-f4-tab">
                            <div class="relative">
                                <input id="from-slider-note-tab" type="range" value="0" min="0" max="5" step="0.5"
                                    class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                                <input id="to-slider-note-tab" type="range" value="5" min="0" max="5" step="0.5"
                                    class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                                <div id="range-background1" class="absolute top-0 left-0"></div>
                            </div>

                            <div class="relative flex justify-between mt-3">
                                <div class="flex items-start">
                                    <input id="from-input-note-tab" type="number" value="0" min="0" max="5" step="0.5"
                                        class="mt-1 w-[39px] text-small focus:ring-0" />
                                    <img src="/public/icones/egg-full.svg" width="11" class="mt-1">
                                </div>
                                <div class="flex items-start">
                                    <input id="to-input-note-tab" type="number" value="5" min="0" max="5" step="0.5"
                                        class="mt-1 w-[39px] text-small focus:ring-0" />
                                    <img src="/public/icones/egg-full.svg" width="11" class="mt-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full border border-black p-3 gap-4">
                        <div class="flex justify-between cursor-pointer" id="button-f5-tab">
                            <p>Prix</p>
                            <p id="arrow-f5-tab">></p>
                        </div>
                        <div class="hidden flex flex-wrap" id="developped-f5-tab">
                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <input id="from-slider-price-tab" type="range" value="0" min="0" max="99"
                                        class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                                    <input id="to-slider-price-tab" type="range" value="99" min="0" max="99"
                                        class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                                    <div id="range-background-price-tab" class="absolute top-0 left-0"></div>
                                </div>

                                <div class="relative flex justify-between mt-3">
                                    <div class="flex items-center">
                                        <input id="from-input-price-tab" type="number" value="0" min="0" max="99"
                                            class="mt-1 w-[34px] text-small focus:ring-0" />
                                        €
                                    </div>
                                    <div class="flex items-center">
                                        <input id="to-input-price-tab" type="number" value="99" min="0" max="99"
                                            class="mt-1 w-[34px] text-small focus:ring-0" />
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
                            if ($i < 10) {
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
        <div
            class="block md:hidden p-4 h-16 w-full bg-bgBlur/75 backdrop-blur border-t-2 border-black fixed bottom-0 flex items-center justify-between">
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
                <div class="hidden md:hidden absolute bottom-[72px] right-2 z-20 bg-white border border-black p-2 max-w-48 flex flex-col gap-4"
                    id="sort-section-tel">
                    <a href="<?php echo ($_GET['sort'] === 'rating-ascending') ? '/' : '?sort=rating-ascending'; ?>"
                        class="flex items-center <?php echo ($_GET['sort'] == 'rating-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Note croissante</p>
                    </a>
                    <a href="<?php echo ($_GET['sort'] === 'rating-descending') ? '/' : '?sort=rating-descending'; ?>"
                        class="flex items-center <?php echo ($_GET['sort'] == 'rating-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Note décroissante</p>
                    </a>
                    <a href="<?php echo ($_GET['sort'] === 'price-ascending') ? '/' : '?sort=price-ascending'; ?>"
                        class="flex items-center <?php echo ($_GET['sort'] === 'price-ascending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Prix croissant</p>
                    </a>
                    <a href="<?php echo ($_GET['sort'] === 'price-descending') ? '/' : '?sort=price-descending'; ?>"
                        class="flex items-center <?php echo ($_GET['sort'] == 'price-descending') ? 'font-bold' : ''; ?> hover:text-primary duration-100">
                        <p>Prix décroissant</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div id="footer"></div>

    <!-- MENU FILTRE TÉLÉPHONE -->
    <div class="block md:hidden flex flex-col justify-between absolute w-full h-full bg-base100 -translate-x-full duration-200 z-50"
        id="filtres">
        <div>
            <div class="p-4 gap-4 flex justify-start items-center h-20 border-b-2 border-black">
                <i class="text-3xl fa-solid fa-circle-xmark hover:cursor-pointer" onclick="toggleFiltres()"></i>
                <h1 class="text-h1">Filtres</h1>
            </div>

            <div class="w-full">
                <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f1-tel">
                        <p>Catégorie</p>
                        <p id="arrow-f1-tel">></p>
                    </div>
                    <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f1-tel">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="restauration" name="restauration" />
                            <label for="restauration">Restauration</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="activite" name="activite" />
                            <label for="activite">Activité</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="spectacle" name="spectacle" />
                            <label for="spectacle">Spectacle</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="visite" name="visite" />
                            <label for="visite">Visite</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="parc_attraction" name="parc_attraction" />
                            <label for="parc_attraction">Parc d'attraction</label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f2-tel">
                        <p>Disponibilité</p>
                        <p id="arrow-f2-tel">></p>
                    </div>
                    <div class="developped hidden text-small flex flex-wrap gap-4" id="developped-f2-tel">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" class="mb-1" id="open" name="open" />
                            <label for="open">Ouvert</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="mb-1" id="closes" name="closes" />
                            <label for="closes">Fermé</label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f3-tel">
                        <p>Localisation</p>
                        <p id="arrow-f3-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-nowrap w-full items-center gap-4" id="developped-f3-tel">
                        <div class="text-nowrap text-small flex items-center gap-2 w-full">
                            <label for="loc">Code postal</label>
                            <label class="text-[#999999]" for="loc">ou</label>
                            <label for="loc">Ville</label>
                            <input id="loc" type="text"
                                class="w-full bg-base100 border border-[#999999] rounded-lg p-1 focus:ring-0" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f4-tel">
                        <p>Note générale</p>
                        <p id="arrow-f4-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-col w-full" id="developped-f4-tel">
                        <div class="relative">
                            <input id="from-slider-note-tel" type="range" value="0" min="0" max="5" step="0.5"
                                class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                            <input id="to-slider-note-tel" type="range" value="5" min="0" max="5" step="0.5"
                                class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                            <div id="range-background-note-tel" class="absolute top-0 left-0"></div>
                        </div>

                        <div class="relative flex justify-between mt-3">
                            <div class="flex items-start">
                                <input id="from-input-note-tel" type="number" value="0" min="0" max="5" step="0.5"
                                    class="bg-base100 text-small mt-1 w-[39px] focus:ring-0" />
                                <img src="/public/icones/egg-full.svg" width="11" class="mt-1">
                            </div>
                            <div class="flex items-start">
                                <input id="to-input-note-tel" type="number" value="5" min="0" max="5" step="0.5"
                                    class="bg-base100 text-small mt-1 w-[39px] focus:ring-0" />
                                <img src="/public/icones/egg-full.svg" width="11" class="mt-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full border-b-2 border-black p-3 gap-4">
                    <div class="flex justify-between cursor-pointer" id="button-f5-tel">
                        <p>Prix</p>
                        <p id="arrow-f5-tel">></p>
                    </div>
                    <div class="developped hidden flex flex-wrap" id="developped-f5-tel">
                        <div class="flex flex-col w-full">
                            <div class="relative">
                                <input id="from-slider-price-tel" type="range" value="0" min="0" max="99"
                                    class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-1" />
                                <input id="to-slider-price-tel" type="range" value="99" min="0" max="99"
                                    class="absolute w-full h-2 rounded-lg appearance-none pointer-events-auto z-2" />

                                <div id="range-background-price-tel" class="absolute top-0 left-0"></div>
                            </div>

                            <div class="relative flex justify-between mt-3">
                                <div class="flex items-center">
                                    <input id="from-input-price-tel" type="number" value="0" min="0" max="99"
                                        class="bg-base100 text-small mt-1 w-[34px] focus:ring-0" />
                                    €
                                </div>
                                <div class="flex items-center">
                                    <input id="to-input-price-tel" type="number" value="99" min="0" max="99"
                                        class="bg-base100 text-small mt-1 w-[34px] focus:ring-0" />
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
    // Fonction pour afficher ou masquer un conteneur de filtres
    function toggleFiltres() {
        let filtres = document.querySelector('#filtres');

        if (filtres) {
            filtres.classList.toggle('active'); // Alterne la classe 'active'
        }
    }
</script>

<script src="/scripts/filtersAndSorts.js"></script>