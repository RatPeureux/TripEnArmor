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
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/facture_controller.php';
        session_start();
        $stmt = $dbh->prepare("SELECT * FROM sae_db._facture");
        $stmt->execute();
        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $numero = $factures[0]['numero'];
        $designation = $factures[0]['designation'];

        $pro = verifyPro();
        $idPro = $_SESSION['id_pro'];
        $factureController = new FactureController;
        $facture = $factureController->getInfoFacture($numero, $designation);

        if (isset($_SESSION['id_pro'])) { 
            $stmtOffre = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true AND id_pro = :id_pro");
            $stmtOffre->bindParam(':id_pro', $_SESSION['id_pro'], PDO::PARAM_INT);
            if ($stmtOffre->execute()) {
            $offresDuPro = $stmtOffre->fetchAll(PDO::FETCH_ASSOC);
            if (count($offresDuPro) > 0) {
                echo "<p>Voici vos offres en ligne :</p>";
                ?>

                <select name="offre" id="offre">
                <option value="" disabled selected>Choisir une offre</option>
                <?php
                foreach ($offresDuPro as $offre) {
                    echo "<option value='" . htmlspecialchars($offre['id_offre']) . "'>" . htmlspecialchars($offre['titre']) . "</option>";
                }
                ?>
                </select>

                <script>
                document.getElementById('offre').addEventListener('change', function() {
                    if (this.value) {
                    document.getElementById('facture-details').style.display = 'block';
                    }
                });

                function closePopup() {
                    document.getElementById('facture-details').style.display = 'none';
                }
                </script>

                <div id="facture-details" class="border border-black p-5 mt-5 mx-auto max-w-4xl" style="display:none;">
                <?php
                    $TVA = 20;
                    $stmtPro = $dbh->prepare("SELECT * FROM sae_db._pro_prive WHERE id_compte = :id_pro");
                    $stmtPro->bindParam(':id_pro', $_SESSION['id_pro'], PDO::PARAM_INT);
                    $stmtPro->execute();
                    $proDetails = $stmtPro->fetch(PDO::FETCH_ASSOC);
                    $stmtAdresse = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE id_adresse = :id_adresse");
                    $stmtAdresse->bindParam(':id_adresse', $proDetails['id_adresse'], PDO::PARAM_INT);
                    $stmtAdresse->execute();
                    $adresseDetails = $stmtAdresse->fetch(PDO::FETCH_ASSOC);
                    $stmtOffre = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_offre = :id_offre");
                    $stmtOffre->bindParam(':id_offre', $facture['id_offre'], PDO::PARAM_INT);
                    $stmtOffre->execute();
                    $offreDetails = $stmtOffre->fetch(PDO::FETCH_ASSOC);
                    $stmtVueFactureQuantite = $dbh->prepare('SELECT * FROM sae_db.vue_facture_quantite WHERE "Numéro de Facture" = :numero');
                    $stmtVueFactureQuantite->bindParam(':numero', $numero, PDO::PARAM_INT);
                    $stmtVueFactureQuantite->execute();
                    $vueFactureQuantite = $stmtVueFactureQuantite->fetchAll(PDO::FETCH_ASSOC);
                    $stmtVueFactureTotaux = $dbh->prepare('SELECT * FROM sae_db.vue_facture_totaux WHERE "Numéro de Facture" = :numero');
                    $stmtVueFactureTotaux->bindParam(':numero', $numero, PDO::PARAM_INT);
                    $stmtVueFactureTotaux->execute();
                    $vueFactureTotaux = $stmtVueFactureTotaux->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="bg-white p-10 shadow-lg rounded-lg w-full max-w-4xl mx-auto">
                    <div class="flex justify-between items-center border-b pb-5 mb-5">
                    <div>
                        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($proDetails['nom_pro']); ?></h1>
                        <p><?php echo htmlspecialchars($adresseDetails['numero']) . " " . htmlspecialchars($adresseDetails['odonyme']) . " " . htmlspecialchars($adresseDetails['complement']); ?><br><?php echo htmlspecialchars($adresseDetails['code_postal']) . ' - ' . htmlspecialchars($adresseDetails['ville']); ?></p>
                        <p><?php echo htmlspecialchars($proDetails['num_tel']); ?></p>
                        <p><?php echo htmlspecialchars($proDetails['email']); ?></p>
                    </div>
                    <div>
                        <p>SIRET : <?php echo htmlspecialchars($proDetails['num_siren']); ?> / TVA <?php echo $TVA ?>%</p>
                    </div>
                    </div>

                    <h2 class="text-xl font-bold mb-5"> <?php echo htmlspecialchars($offresDuPro['titre']) ?> </h2>

                    <div class="mb-5">
                    <p>Date d'émission : <strong> <?php echo htmlspecialchars($facture['date_emission']); ?> </strong></p>
                    <p>Période de validité : <strong><?php echo htmlspecialchars($facture['quantite']); ?> jours</strong></p>
                    </div>

                    <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-blue-100">
                        <tr>
                        <th class="border border-gray-300 p-2 text-left">Désignation</th>
                        <th class="border border-gray-300 p-2 text-right">Quantité</th>
                        <th class="border border-gray-300 p-2 text-right">Unité</th>
                        <th class="border border-gray-300 p-2 text-right">Prix Unitaire</th>
                        <th class="border border-gray-300 p-2 text-right">TVA</th>
                        <th class="border border-gray-300 p-2 text-right">Total HT</th>
                        <?php
                        foreach ($vueFactureQuantite as $ligne) {
                        echo "<tr>";
                        echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($ligne['Service']) . "</td>";
                        echo "<td class='border border-gray-300 p-2 text-right'>" . htmlspecialchars($ligne['Quantité']) . "</td>";
                        echo "<td class='border border-gray-300 p-2 text-right'>" . "jours ou semaines" . "</td>";
                        echo "<td class='border border-gray-300 p-2 text-right'>" . htmlspecialchars($ligne['Prix Unitaire HT (â‚¬)']) . " €</td>";
                        echo "<td class='border border-gray-300 p-2 text-right'>" . $TVA . " %</td>";
                        echo "<td class='border border-gray-300 p-2 text-right'>" . number_format((float)$ligne['Montant HT (â‚¬)'], 2, '.', '') . " €</td>";
                        echo "</tr>";
                        }
                        ?>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>

                    <div class="mt-5">
                    <div class="flex justify-end">
                        <div class="w-1/3">
                        <div class="flex justify-between">
                            <span>Total HT</span>
                            <span> <?php echo (number_format((float)$vueFactureTotaux[0]['Total HT (â‚¬)'])); ?> €</span>
                        </div>
                        <div class="flex justify-between">
                            <span>TVA <?php echo $TVA ?>%</span>
                            <span><?php echo (number_format((float)$vueFactureTotaux[0]['Total TTC (â‚¬)'])) - (number_format((float)$vueFactureTotaux[0]['Total HT (â‚¬)'])) ?> €</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Total TTC</span>
                            <span><?php echo number_format((float)$vueFactureTotaux[0]['Total TTC (â‚¬)']) ?> €</span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>  
                <?php
            } else {
                echo "<p>Vous n'avez pas d'offres en ligne.</p>";
            }
            } else {
            echo "Erreur lors de l'exécution de la requête";
            }
        } else {
            echo "La session id_pro n'est pas définie";
        } ?>
    </div>
    </main>

    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
    ?>
</body>

</html>
