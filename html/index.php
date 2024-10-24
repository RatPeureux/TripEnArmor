
<?php
    include("php/authentification.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/output.css">
    <script type="module" src="/scripts/main.js" defer></script>
    <title>PACT - Accueil</title>
    <link rel="icon" type="image" href="/public/images/favicon.png">
</head>
<body class="flex flex-col min-h-screen">

    <div id="header" class="mb-10"></div>

    <?php
        // Connexion avec la bdd
        include('../php-files/connect_params.php');
        $dbh = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtenir l'ensembre des offres
        $stmt = $dbh->prepare("SELECT * FROM sae_db._offre");
        $stmt->execute();
        $toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="/pages/details.php">
            <div class="card active relative bg-base200 rounded-xl flex flex-col">
                <!-- En tête -->
                <div class="en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg">
                    <h3 class="text-center font-bold">Restaurant le Brélévenez</h3>
                    <div class="flex w-full justify-between px-2">
                        <p class="text-small">Le brélévenez</p>
                        <p class="text-small">Restauration</p>
                    </div>
                </div>
                <!-- Image de fond -->
                <img class="h-48 w-full rounded-t-lg object-cover" src="../public/images/image-test.jpg" alt="Image promotionnelle de l'offre">
                <!-- Infos principales -->
                <div class="infos flex items-center justify-center gap-2 px-2 grow">
                    <!-- Localisation -->
                    <div class="localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center">
                        <i class="fa-solid fa-location-dot"></i>
                        <p class="text-small">Lannion</p>
                        <p class="text-small">22300</p>
                    </div>
                    <hr class="h-20 border-black border">
                    <!-- Description -->
                    <div class="description py-2 flex flex-col gap-2 justify-center self-stretch">
                        <div class="p-1 rounded-lg bg-secondary self-center">
                            <p class="text-white text-center text-small font-bold">Petit déjeuner, Dîner, Boissons</p>
                        </div>
                        <p class="overflow-hidden line-clamp-2 text-small">
                            Priscilla en salle, son mari Christophe chef de cuisine et toute l'équipe vous accueillent dans leur restaurant, Ouvert depuis Janvier 2018, dans le quartier Historique De Lannion :" Brélévenez"
                            Quartier célèbre pour son église avec son escalier de 142 marches pour y accéder.
                            Christophe vous propose une cuisine de produits locaux et de saisons.
                            Restaurant ouvert à l'année.
                            Fermé mardi et mercredi toute la journée et le samedi midi.
                            (Parking privé)
                        </p>
                    </div>
                    <hr class="h-20 border-black border">
                    <!-- Notation et Prix -->
                    <div class="localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center">
                        <p class="text-small">€€</p>
                    </div>
                </div>
            </div>
        </a>
    </main>
    
    <!-- VERSION TABLETTE -->
    <main class="hidden md:block grow mx-10 self-center rounded-lg p-2 max-w-[1280px]">
        <div class="flex gap-3">
            <!-- PARTIE GAUCHE (menu) -->
            <div id="menu" class="2"></div>

            <!-- PARTIE DROITE (offres & leurs détails) -->
            <div class="tablette p-4 flex flex-col gap-4">
            
                <h1 class="text-4xl">Toutes les offres</h1>
        
                <?php
                    foreach(array_keys($toutesLesOffres) as $key);
                ?>
                <!--
                ### CARD COMPONENT ! ###
                Composant dynamique (généré avec les données en php)
                Impossible d'en faire un composant pur (statique), donc écrit en HTML pur (copier la forme dans le php)
                -->
                <?php
                if (!$toutesLesOffres) { 
                    echo "<p clas='font-bold'>Vous n'avez aucune offre...</p>";
                } else {
                    foreach($toutesLesOffres as $offre) {

                        // Avoir une variable $pro qui contient les informations du pro actuel.
                        $idPro = $offre['idpro'];
                        $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE id_compte = $idPro");
                        $stmt->execute();
                        $pro = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($pro) {
                            $pro_nom = $pro['nompro'];
                        }

                        // Détails de l'offre
                        $offre_id = $offre['offre_id'];
                        $description = $offre['description_offre'];
                        $resume = $offre['resume_offre'];
                        $option = $offre['option'];
                        $prix_mini = $offre['prix_mini'];
                        $date_mise_a_jour = $offre['date_mise_a_jour'];
                        $titre_offre = $offre['titre'];

                            // Obtenir la catégorie de l'offre
                        $stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_categorie WHERE offre_id = $offre_id");
                        $stmt->execute();
                        $categorie_offre = $stmt->fetch(PDO::FETCH_ASSOC)['type_offre'];

                            // Obtenir la date de mise à jour
                        $date_mise_a_jour = $offre['date_mise_a_jour'];
                        $date_mise_a_jour = new DateTime($date_mise_a_jour);
                        $date_mise_a_jour = $date_mise_a_jour->format('d/m/Y');

                            // Obtenir le type de l'offre (gratuit, standard, premium)
                        $stmt = $dbh->prepare("SELECT * FROM sae_db.vue_offre_type WHERE offre_id = $offre_id");
                        $stmt->execute();
                        $type_offre = $stmt->fetch(PDO::FETCH_ASSOC)['nom_type_offre'];

                            // Détails de l'adresse
                        $adresse_id = $offre['adresse_id'];
                        $stmt = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE adresse_id = $adresse_id");
                        $stmt->execute();
                        $adresse = $stmt->fetch(PDO::FETCH_ASSOC);
                        $code_postal = $adresse['code_postal'];
                        $ville = $adresse['ville'];

                        // ######### CAS DES AFFICHAGES QUI DIFFÈRENT SELON LA CATÉGORIE DE L'OFFRE #########

                            // Afficher les prix ou la gamme de prix si c'est un restaurant
                        $prix_sur_carte;
                        if ($categorie_offre == 'restauration') {
                            $stmt = $dbh->prepare("SELECT * FROM sae_db._restauration WHERE offre_id = $offre_id");
                            $stmt->execute();
                            $prix_sur_carte = $stmt->fetch(PDO::FETCH_ASSOC)['gamme_prix'];
                        } else {
                            $prix_sur_carte = $offre['prix_mini'] . '€';
                        }
                            // Tags pour le restaurant (pour la carte, on prend les types de repas) ou autres si ce n'est pas un restaurant
                        if ($categorie_offre == 'restauration') {
                            $stmt = $dbh->prepare("SELECT type_repas_id FROM sae_db._restaurant_type_repas WHERE restauration_id = $offre_id");
                            $stmt->execute();
                            $repasIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tags = '';
                            // Récup chaque nom de tag, et l'ajouter aux tags
                            foreach($repasIds as $repasId) {
                                $stmt = $dbh->prepare("SELECT nom_type_repas FROM sae_db._type_repas WHERE type_repas_id = $repasId");
                                $stmt->execute();
                                $nom_tag = $stmt->fetch(PDO::FETCH_ASSOC);
                                $tags = $tags . ', ' . $nom_tag;
                            }
                            // Tags pour les autres types d'offre
                        } else {
                            $stmt = $dbh->prepare("SELECT tag_id FROM sae_db._tag_$categorie_offre WHERE id_$categorie_offre = $offre_id");
                            $stmt->execute();
                            $tagIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $tags = '';
                            // Récup chaque nom de tag, et l'ajouter aux tags
                            foreach($tagIds as $tagId) {
                                $stmt = $dbh->prepare("SELECT nom_tag FROM sae_db._tag WHERE tag_id = $tagId");
                                $stmt->execute();
                                $nom_tag = $stmt->fetch(PDO::FETCH_ASSOC);
                                $tags = $tags . ', ' . $nom_tag;
                            }
                        }
                ?>

                <a href="/pages/go_to_details.php?offre_id=<?php echo $offre_id ?>">
                    <div class="card <?php if ($option) echo 'active' ?> relative bg-base200 rounded-lg flex">
                        <!-- Partie gauche -->
                        <div class="gauche grow relative shrink-0 basis-1/2 h-[280px] overflow-hidden">
                            <!-- En tête -->
                            <div class="en-tete absolute top-0 w-72 max-w-full bg-bgBlur/75 backdrop-blur left-1/2 -translate-x-1/2 rounded-b-lg">
                                <h3 class="text-center font-bold"><?php echo $titre_offre ?></h3>
                                <div class="flex w-full justify-between px-2">
                                    <p class="text-small"><?php echo $pro_nom ?></p>
                                    <p class="text-small"><?php echo $categorie_offre ?></p>
                                </div>
                            </div>
                            <!-- Image de fond -->
                            <img class="rounded-l-lg w-full h-full object-cover object-center" src="/public/images/image-test.jpg" alt="Image promotionnelle de l'offre">
                        </div>
                        <!-- Partie droite (infos principales) -->
                        <div class="infos flex flex-col items-center basis-1/2 p-3 gap-3 justify-between">
                            <!-- Description -->
                            <div class="description py-2 flex flex-col gap-2">
                                <div class="p-1 rounded-lg bg-secondary self-center">
                                    <p class="text-white text-center text-small font-bold">
                                        <?php
                                        // Si c'est un restaurant, afficher les types de plats, sinon aficher les tags de l'offre
                                        if ($tags) {
                                            echo $tags;
                                        }
                                        else {
                                            echo 'Aucun tag';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <p class="overflow-hidden line-clamp-5 text-small">
                                    <?php echo $resume ?>
                                </p>
                            </div>
                            <!-- A droite, en bas -->
                            <div class="self-stretch flex flex-col gap-2">
                                <hr class="border-black w-full">
                                <div class="flex justify-around self-stretch">
                                    <!-- Localisation -->
                                    <div class="localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <p class="text-small"><?php echo $ville ?></p>
                                        <p class="text-small"><?php echo $code_postal ?></p>
                                    </div>
                                    <!-- Notation et Prix -->
                                    <div class="localisation flex flex-col flex-shrink-0 gap-2 justify-center items-center">
                                        <p class="text-small"><?php echo $prix_sur_carte ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <?php
                        }
                    }
                ?>

            </div>
        </div>
    </main>

    <div id="footer"></div>
    
</body>
</html>
