<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">

    <script src="/scripts/main.js" type="module"></script>

    <title>PACT</title>

    <script src="/scripts/filtersAndSorts.js"></script>
</head>

<body class="flex flex-col min-h-screen">

    <?php
    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Obtenez l'ensemble des offres à la une avec le tri approprié
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true");
    $stmt->execute();
    $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $dbh->prepare("
        select *
        from sae_db._souscription natural join sae_db._offre
        where nom_option = 'A la une'
        and est_en_ligne = true
        AND (date_annulation IS NULL OR CURRENT_DATE < date_annulation)
        AND CURRENT_DATE <= date_lancement + (nb_semaines * INTERVAL '1 week')
        AND CURRENT_DATE >= date_lancement
    ");
    $stmt->execute();
    $aLaUnes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer toutes les moyennes en une seule requête
    $stmt = $dbh->query("SELECT id_offre, avg FROM sae_db.vue_moyenne");
    $notesMoyennes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Associer les moyennes aux offres
    $notesAssociees = [];
    foreach ($notesMoyennes as $note) {
        $notesAssociees[$note['id_offre']] = floatval($note['avg']);
    }

    // Créer un tableau temporaire enrichi
    $offresAvecNotes = array_map(function ($offre) use ($notesAssociees) {
        $offre['note_moyenne'] = $notesAssociees[$offre['id_offre']] ?? null; // Note null si non trouvée
        return $offre;
    }, $toutesLesOffres);

    usort($offresAvecNotes, function ($a, $b) {
        return $b['note_moyenne'] <=> $a['note_moyenne']; // Tri décroissant
    });

    $meilleuresNotes = $offresAvecNotes;
    ?>


    <header class="flex justify-between items-center z-30 w-full h-16 top-0 mx-auto max-w-[1280px] px-4">
        <div class="flex items-center justify-between md:gap-4">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <img src="/public/icones/logo.svg" alt="Logo de TripEnArvor : Moine macareux" width="50">
            </a>
        </div>

        <!-- Menu -->
        <div class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center text-sm">
            <a class="text-nowrap p-2 hover:bg-base100 border-r border-base100 px-4" href="/offres/a-la-une">À la
                Une</a>
            <a class="text-nowrap p-2 hover:bg-base100 px-4" href="/offres">Toutes les offres</a>
        </div>

        <!-- Actions Utilisateur -->
        <div class="flex items-center text-sm gap-4">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
            if (isConnectedAsMember()) { ?>
                    <!-- Si connecté -->
                    <a href="/scripts/logout.php" class="hidden md:block flex flex-col items-center"
                        onclick="return confirmLogout()">
                        <div class="text-black border border-secondary px-4 py-2 rounded-full">
                            <p>Se déconnecter</p>
                        </div>
                    </a>
                    <a href="/compte">
                        <i class="text-3xl fa-regular fa-user"></i>
                    </a>
            <?php } else { ?>
                    <!-- Si non connecté -->
                    <a href="/connexion" class="md:hidden">
                        <i class="text-3xl fa-regular fa-user"></i>
                    </a>
                    <a href="/connexion" class="hidden md:block">
                        <div
                            class="text-white border border-secondary bg-secondary px-4 py-2 rounded-full hover:bg-secondary/90">
                            <p>Se connecter</p>
                        </div>
                    </a>
            <?php } ?>
        </div>
    </header>

    <main class="self-center align-center w-full grow justify-between max-w-[1280px] px-2 pb-2">
        <div class="w-full flex justify-center gap-10 text-center">
            <img src="public/images/plumeGN.png" alt="Plume du moine macareux"
                class="h-full hidden md:flex max-h-[170px] space-x-2 pb-1 -rotate-[20deg]">
            <a href="/"
                class="font-cormorant uppercase text-center text-[20vw] md:text-[10rem] tracking-widest text-7xl ml-8 mb-4">PACT</a>
            <img src="public/images/plumeDN.png" alt="Plume du moine macareux"
                class="h-full max-h-[170px] hidden md:flex pb-1 rotate-[20deg]">
        </div>

        <div class="searchOn hidden md:flex justify-between text-center items-center mb-2">
            <h1 class="cursor-pointer text-xl border-b border-secondary hover:text-secondary" id="all" tabindex="0">
                Tout rechercher
            </h1>
            <h1 class="cursor-pointer text-xl hover:text-secondary" id="restaurants" tabindex="0">
                Restaurants
            </h1>
            <h1 class="cursor-pointer text-xl hover:text-secondary" id="spectacles" tabindex="0">
                Spectacles
            </h1>
            <h1 class="cursor-pointer text-xl hover:text-secondary" id="activites" tabindex="0">
                Activités
            </h1>
            <h1 class="cursor-pointer text-xl hover:text-secondary" id="visites" tabindex="0">
                Visites
            </h1>
            <h1 class="cursor-pointer text-xl hover:text-secondary" id="attractions" tabindex="0">
                Attractions
            </h1>
        </div>

        <div class="searchOn text-center md:hidden mb-2">
            <select class="text-center text-xl bg-white border-b border-secondary p-1 cursor-pointer focus:outline-none"
                id="search-category">
                <option class="text-left" value="all">Tout rechercher</option>
                <option class="text-left" value="restaurants">Restaurants</option>
                <option class="text-left" value="spectacles">Spectacles</option>
                <option class="text-left" value="activites">Activités</option>
                <option class="text-left" value="visites">Visites</option>
                <option class="text-left" value="attractions">Attractions</option>
            </select>
        </div>

        <!-- Barre de recherche -->
        <div class="relative flex-2 max-w-full mx-2 mb-8">
            <div class="relative flex items-center">
                <input type="text" id="search-field" placeholder="Rechercher par tags..."
                    class="rounded-full w-full border border-primary p-2  h-12 pl-10 pr-14 focus:outline-none focus:ring-2 focus:ring-primary transition duration-200"
                    aria-label="Recherche" autocomplete="off">
                <div class="absolute right-4 flex items-center justify-center transform -translate-y-1/2">
                    <i class="fa-solid fa-magnifying-glass fa-lg cursor-pointer" id="search-btn"></i>
                </div>
                <!-- Bouton de suppression -->
                <button class="hidden absolute right-2 min-w-max flex items-center justify-center bg-white px-2 py-1"
                    id="clear-tags-btn">
                    <i class="text-xl fa-solid fa-times cursor-pointer"></i>
                </button>
            </div>
            <!-- Dropdown de recherche -->
            <div class="absolute top-full left-0 right-0 bg-white border border-base200 shadow-md mt-2 hidden z-10"
                id="search-menu">
            </div>
        </div>

        <a class="cursor-pointer group" href="/offres/a-la-une">
            <h1 class="text-3xl ">À la Une<span
                    class="font-normal xl:opacity-0 group-hover:opacity-100 duration-200">&nbsp;&gt;</span></h1>
        </a>

        <?php
        // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
        if (!$aLaUnes) { ?>
                <div class="h-72 md:min-w-full flex items-center justify-center gap-4 mb-8 md:mb-16">
                    <?php echo "<p class=' text-2xl'>Il n'existe aucune offre...</p>"; ?>
                </div>
        <?php } else { ?>
                <div class="overflow-x-auto scroll-hidden md:min-w-full flex gap-4 mb-8 md:mb-16" id="no-matches-2">
                    <?php $i = 0;
                    foreach ($aLaUnes as $offre) {
                        if ($i > -1) {
                            require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre_accueil.php';
                            $i++;
                        }
                    } ?>
                </div>
        <?php } ?>

        <h1 class="text-3xl ">Nos meilleures offres</h1>

        <?php
        // Obtenir les informations de toutes les offres et les ajouter dans les mains du tel ou de la tablette
        if (!$meilleuresNotes) { ?>
                <div class="h-72 md:min-w-full flex items-center justify-center gap-4 mb-0 md:mb-12">
                    <?php echo "<p class=' text-2xl'>Il n'existe aucune offre...</p>"; ?>
                </div>
        <?php } else { ?>
                <div class="overflow-x-auto scroll-hidden md:min-w-full flex gap-4 mb-4 md:mb-12" id="no-matches">
                    <?php
                    $categoriesOrdre = [
                        'restauration' => null,
                        'spectacle' => null,
                        'activite' => null,
                        'visite' => null,
                        'parc_attraction' => null,
                    ];

                    // Parcourir les offres
                    foreach ($meilleuresNotes as $offre) {

                        $stmt = $dbh->prepare("SELECT count FROM sae_db.vue_moyenne WHERE id_offre = :id_offre");
                        $stmt->bindParam(':id_offre', $offre['id_offre']);
                        $stmt->execute();
                        $moyenne = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($moyenne) {
                            $stmt = $dbh->prepare("SELECT type_offre FROM sae_db.vue_offre_categorie WHERE id_offre = :id_offre");
                            $stmt->bindParam(':id_offre', $offre['id_offre']);
                            $stmt->execute();
                            $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($categorie && isset($categorie['type_offre'])) {
                                $typeOffre = $categorie['type_offre'];

                                // Ajouter l'offre dans la catégorie correspondante si elle n'est pas encore définie
                                if (array_key_exists($typeOffre, $categoriesOrdre) && $categoriesOrdre[$typeOffre] === null) {
                                    $categoriesOrdre[$typeOffre] = $offre;
                                }
                            }
                        }
                    }

                    // Reconstituer $temp dans l'ordre des catégories
                    $meilleuresNotes = array_filter($categoriesOrdre); // Filtrer les catégories non attribuées
                
                    $iOffres = 0;
                    foreach ($meilleuresNotes as $offre) {
                        if ($iOffres < 5) {
                            require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre_accueil.php';
                        }
                        $iOffres++;
                    } ?>
                </div>
        <?php } ?>
    </main>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sélectionne les éléments nécessaires
            const titres = document.querySelectorAll('.searchOn h1');
            const searchField = document.getElementById('search-field');
            const selectMenu = document.getElementById('search-category');

            // Placeholder correspondant à chaque catégorie
            const placeholders = {
                all: "Rechercher par tags...",
                restaurants: "Rechercher des restaurants par tags...",
                spectacles: "Rechercher des spectacles par tags...",
                activites: "Rechercher des activités par tags...",
                visites: "Rechercher des visites par tags...",
                attractions: "Rechercher des parcs d'attractions par tags..."
            };

            // Fonction pour gérer le clic sur les h1
            const handleH1Click = (titre) => {
                const id = titre.id;

                // Retire la classe de soulignement de tous les h1
                titres.forEach(t => t.classList.remove('border-b', 'border-secondary'));

                // Ajoute la classe de soulignement au h1 cliqué
                titre.classList.add('border-b', 'border-secondary');

                // Met à jour le placeholder
                searchField.placeholder = placeholders[id] || "Rechercher...";

                // Met à jour la valeur du select pour qu'elle corresponde
                selectMenu.value = id;
            };

            // Fonction pour gérer le changement du select
            const handleSelectChange = () => {
                const selectedValue = selectMenu.value;

                // Met à jour le placeholder
                searchField.placeholder = placeholders[selectedValue] || "Rechercher...";

                // Met à jour le style des h1 pour refléter la sélection
                titres.forEach(t => {
                    if (t.id === selectedValue) {
                        t.classList.add('border-b', 'border-secondary');
                    } else {
                        t.classList.remove('border-b', 'border-secondary');
                    }
                });
            };

            // Ajoute les écouteurs sur les h1
            titres.forEach(titre => {
                titre.addEventListener('click', () => handleH1Click(titre));
            });

            // Ajoute un écouteur sur le select
            selectMenu.addEventListener('change', handleSelectChange);

            // Gestion de l'affichage des offres "À la Une"
            const offres = document.querySelectorAll('#no-matches-2 .card');
            let anyVisible = false;

            offres?.forEach((offre) => {
                if (offre.classList.contains('active')) {
                    anyVisible = true;
                    offre.classList.remove('hidden');
                } else {
                    offre.classList.add('hidden');
                }
            });

            const noMatchesContainer = document.querySelector('#no-matches-2');
            if (!noMatchesContainer) {
                console.error('Le conteneur #no-matches-2 est introuvable.');
                return;
            }

            const noMatchesMessage = document.getElementById('no-matches-message');
            if (!anyVisible) {
                if (!noMatchesMessage) {
                    const messageContainer = document.createElement('div');
                    messageContainer.classList.add('w-full', 'h-full', 'h-full');
                    const message = document.createElement('div');
                    message.id = 'no-matches-message';
                    const content = document.createElement('p');
                    content.textContent = 'Aucune offre n\'est "À la Une".';
                    message.classList.add('flex', 'justify-center', 'items-center', 'text-2xl', 'h-[27rem]');
                    message.appendChild(content);
                    messageContainer.appendChild(message);
                    noMatchesContainer.appendChild(messageContainer);
                }
            } else {
                noMatchesMessage?.remove();
            }
        });
    </script>

</body>

</html>