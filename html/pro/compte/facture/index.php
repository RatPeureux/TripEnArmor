

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image" href="/public/images/favicon.png">
    <link rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/styles/config.js"></script>
    <script type="module" src="/scripts/main.js" defer></script>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>

    <title>Facture - Professionnel - PACT</title>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <header class="z-30 w-full bg-white flex justify-center p-4 h-20 border-b-2 border-black top-0">
        <div class="flex w-full items-center">
            <a href="#" onclick="toggleMenu()" class="mr-4 flex gap-4 items-center hover:text-primary duration-100">
                <i class="text-3xl fa-solid fa-bars"></i>
            </a>
            <p class="text-h2">
                <a href="/pro/compte">Mon compte</a>
                >
                <a href="pro/compte/facture" class="underline">Facture</a>
            </p>
        </div>
    </header>
    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>
    <main class="md:w-full mt-0 m-auto max-w-[1280px] p-2">

    <div>
        <p>Facture</p>
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/facture_controller.php';
        session_start();
        $pro = verifyPro();
        $factureController = new FactureController;
        $facture = $factureController->getInfoFacture($pro['id_pro']);
        
        // récupérer les différentes offres du professionnel
        if (isset($_SESSION['id_pro'])) {
            $stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true AND id_pro = :id_pro");
            $stmt->bindParam(':id_pro', $_SESSION['id_pro'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                $offresDuPro = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<p>Voici les offres que vous avez créées :</p>";
                foreach ($offresDuPro as $offre) {
                    ?>

                        <select name="offre" id="offre">
                            <option value="" disabled selected>Choisir une offre</option>
                            <?php
                            foreach ($offresDuPro as $offre) {
                                echo "<option value='choisie'" . $offre['id_offre'] . "'>" . htmlspecialchars($offre['titre']) . "</option>";
                            }
                            ?>
                        </select>

                        <!-- une fois qu'on sélectionne une offre, ça ouvre une page (style pop-up) et ça place les emplacements de la facture (du style genre la date, le prix, faire un recap des options et du type de l'offre...) -->
                        <div>
                            <p>Offre choisie : <?php echo $offre['titre'] ?></p>
                            <p>Date de la facture : <?php echo $facture['jour_en_ligne'] ?></p>
                            <p>Prix : <?php echo $offre['prix_mini'] ?>€</p>
                            <p>Options : <?php echo $offre['options'] ?></p>
                            <p>Type de l'offre : <?php echo $offre['id_type_offre'] ?></p>
                        </div>

                    <?php

                }
            } else {
                echo "Erreur lors de l'exécution de la requête";
            }
        } else {
            echo "La session id_pro n'est pas définie";
        }  ?>
    </div>

    
        
    </main>


    <!-- FOOTER -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
</body>

</html>
