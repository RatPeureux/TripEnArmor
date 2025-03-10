<?php
session_start();

// Enlever les informations gardées lors de l'étape de connexion quand on reveint à la page (retour en arrière)
unset($_SESSION['data_en_cours_connexion']);

// Vérifier si le pro est bien connecté
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$pro = verifyPro();

// Fonction utilitaires
if (!function_exists('chaineVersMot')) {
    function chaineVersMot($str): string
    {
        return str_replace('_', " d'", ucfirst($str));
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/style.css">

    <script type="module" src="/scripts/main.js"></script>

    <title>Mes offres - Professionnel - PACT</title>
</head>

<body class="flex flex-col min-h-screen">

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/header.php';
    ?>

    <?php
    // Connexion avec la bdd
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Obtenir l'ensembre des offres du professionnel identifié
    $stmt = $dbh->prepare("SELECT * FROM sae_db._offre JOIN sae_db._professionnel ON sae_db._offre.id_pro = sae_db._professionnel.id_compte WHERE id_compte = :id_pro");
    $stmt->bindParam(':id_pro', $pro['id_compte']);
    $stmt->execute();
    $toutesMesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['sort'])) {
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
        }, $toutesMesOffres);

        // Effectuer le tri
        if ($_GET['sort'] === 'note-ascending') {
            usort($offresAvecNotes, function ($a, $b) {
                if (is_null($a['note_moyenne']) && is_null($b['note_moyenne'])) {
                    return 0;
                }
                if (is_null($a['note_moyenne'])) {
                    return 1;
                }
                if (is_null($b['note_moyenne'])) {
                    return -1;
                }

                return $a['note_moyenne'] <=> $b['note_moyenne'];
            });

            // Réassigner les offres triées
            $toutesLesOffres = $offresAvecNotes;
        } else if ($_GET['sort'] === 'note-descending') {
            usort($offresAvecNotes, function ($a, $b) {
                if (is_null($a['note_moyenne']) && is_null($b['note_moyenne'])) {
                    return 0;
                }
                if (is_null($a['note_moyenne'])) {
                    return 1;
                }
                if (is_null($b['note_moyenne'])) {
                    return -1;
                }

                return $b['note_moyenne'] <=> $a['note_moyenne'];
            });

            // Réassigner les offres triées
            $toutesLesOffres = $offresAvecNotes;
        } else if ($_GET['sort'] == 'price-ascending') {
            usort($toutesLesOffres, function ($a, $b) {
                if (is_null($a['prix_mini']) && is_null($b['prix_mini'])) {
                    return 0;
                }
                if (is_null($a['prix_mini'])) {
                    return -1;
                }
                if (is_null($b['prix_mini'])) {
                    return 1;
                }
                return $a['prix_mini'] <=> $b['prix_mini'];
            });
        } else if ($_GET['sort'] == 'price-descending') {
            usort($toutesLesOffres, function ($a, $b) {
                if (is_null($a['prix_mini']) && is_null($b['prix_mini'])) {
                    return 0;
                }
                if (is_null($a['prix_mini'])) {
                    return -1;
                }
                if (is_null($b['prix_mini'])) {
                    return 1;
                }
                return $b['prix_mini'] <=> $a['prix_mini'];
            });
        }
    }

    $prix_mini_max = 0;

    foreach ($toutesMesOffres as $offre) {
        $prix_mini = $offre['prix_mini'];
        if ($prix_mini !== null && $prix_mini !== '') {
            if ($prix_mini_max === 0) {
                $prix_mini_max = $prix_mini;
            } else {
                $prix_mini_max = max($prix_mini_max, $prix_mini);
            }
        }
    }
    ?>

    <main
        class="mx-10 self-center w-full grow p-2 flex flex-col justify-center items-center 2xl:flex-row 2xl:gap-4 2xl:justify-start 2xl:items-start">
        <!-- TOUTES LES OFFRES (offre & détails) -->
        <div class="w-full xl:max-w-7xl flex-shrink-0 grow tablette p-4 flex flex-col">

            <!-- Conteneur des tags (!!! RECHERCHE) -->
            <div class="flex flex-wrap gap-4" id="tags-container"></div>

            <div class="w-full flex justify-between items-end mb-2">
                <div class="flex items-center gap-4">
                    <h1 class="text-3xl">Mes offres</h1>
                    <!-- Bouton de création d'offre -->
                    <a href="/pro/offre/creer" class="self-center bg-primary text-sm text-white py-2 px-4 rounded-full inline-flex items-center border border-primary hover:text-white hover:bg-primary/90 hover:border-primary/90 
                    focus:scale-[0.97] <?php
                    if (!$toutesMesOffres) {
                        echo "hidden";
                    } ?>">
                        Créer offre +
                    </a>
                </div>

                <!-- BOUTONS DE FILTRES ET DE TRIS TABLETTE -->
                <div class="hidden md:flex justify-center items-center gap-8">
                    <a class="cursor-pointer flex items-center gap-2 hover:text-primary duration-100"
                        id="filter-button-tab">
                        <i class="text xl fa-solid fa-filter"></i>
                        <p>Filtrer</p>
                    </a>

                    <a class="cursor-pointer flex items-center gap-2 hover:text-primary duration-100"
                        id="sort-button-tab">
                        <i class="text xl fa-solid fa-sort"></i>
                        <p>Trier par</p>
                    </a>
                </div>
            </div>

            <!-- Inclusion des interfaces de filtres/tris (tablette et +) -->
            <?php
            include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtres_tris_tab_pro.php';
            ?>

            <?php
            // Obtenir les informations des offres du pro
            if (!$toutesMesOffres) { ?>
                <div class="md:min-w-full flex flex-col justify-center gap-4">
                    <!-- <?php echo "<p class='mt-4 text-2xl'>Vous n'avez aucune offre...</p>"; ?> -->
                    <!-- <?php echo "<p class='mt-4  text-2xl'>Créer votre toute première offre dès maintenant !</p>"; ?> -->
                    <div class="flex justify-center">
                        <a href="/pro/offre/creer" class="self-center w-full h-80 text-center  text-gray-500 py-2 px-4  inline-flex items-center justify-center border border-dashed border-gray-500 hover:border-primary hover:text-primary animate-scale
                    focus:scale-[0.97]"> Créer votre première offre dès maintenant ! </a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="md:min-w-full flex flex-col gap-4" id="no-matches">
                    <?php foreach ($toutesMesOffres as $offre) {
                        // Afficher la carte (!!! défnir la variable $mode_carte !!!)
                        $mode_carte = 'pro';
                        require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/carte_offre.php';
                    } ?>
                </div>
            <?php } ?>

        </div>
        <div class="w-full h-full xl:max-w-7xl grow p-4">
            <div class="flex 3xl:flex-col min-[1760px]:flex-row justify-between items-center mb-4">
                <h2 class="text-3xl">
                    Notifications
                </h2>

                <form action="/scripts/mark_all_as_read.php" method="POST" class="underline cursor-pointer">
                    <input type="hidden" name="id_pro" value="<?php echo $_SESSION['id_pro']; ?>">
                    <button type="submit"
                        class="rounded-full bg-secondary text-white text-sm py-2 px-4 border border-secondary hover:text-white hover:bg-secondary/90 hover:border-secondary/90 focus:scale-[0.97]">
                        Tout supprimer
                    </button>
                </form>
            </div>
            <?php
            require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/notification_view.php';
            ?>
        </div>
    </main>

    <!-- Inclusion des interfaces de filtres/tris (téléphone) -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/filtres_tris_tab_pro.php';
    ?>

    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/view/footer.php';
    ?>
</body>

</html>