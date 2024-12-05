<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

$pro = verifyPro();
?>

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
<body class="min-h-screen flex flex-col">

    <div id="menu-pro">
        <?php
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/menu-pro.php';
        ?>
    </div>

    <!-- Inclusion du header -->
    <?php
    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/header-pro.php';
    ?>

    <main class="grow md:w-full mt-0 m-auto max-w-[1280px] p-2">
        <p class="text-h3 p-4">
            <a href="/pro/compte">Mon compte</a>
            >
            <a href="/pro/compte/facture" class="underline">Facture</a>
        </p>

        <hr class="mb-8">

        <div class="block">
            <?php
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
            require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/facture_controller.php';
            
            $stmt = $dbh->prepare("SELECT * FROM sae_db._facture");
            $stmt->execute();
            $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $numero = $factures[0]['numero'];
            $designation = $factures[0]['designation'];

            $idPro = $_SESSION['id_pro'];
            $factureController = new FactureController;
            $facture = $factureController->getInfoFacture($numero, $designation);

            if (isset($_SESSION['id_pro'])) { 
                $stmtOffre = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true AND id_pro = :id_pro");
                $stmtOffre->bindParam(':id_pro', $_SESSION['id_pro'], PDO::PARAM_INT);
                if ($stmtOffre->execute()) {
                $offresDuPro = $stmtOffre->fetchAll(PDO::FETCH_ASSOC);
                // var_dump($offresDuPro); 
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
                            // print_r($proDetails);
                            $stmtAdresse = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE id_adresse = :id_adresse");
                            $stmtAdresse->bindParam(':id_adresse', $proDetails['id_adresse'], PDO::PARAM_INT);
                            $stmtAdresse->execute();
                            $adresseDetails = $stmtAdresse->fetch(PDO::FETCH_ASSOC);
                            // var_dump($offre['id_offre']);
                            $stmtTypeOffre = $dbh->prepare("SELECT * FROM sae_db._type_offre WHERE id_type_offre = :id_type_offre");
                            $stmtTypeOffre->bindParam(':id_type_offre', $offre['id_type_offre'], PDO::PARAM_INT);
                            $stmtTypeOffre->execute();
                            $typeOffre = $stmtTypeOffre->fetch(PDO::FETCH_ASSOC);
                            // var_dump($typeOffre);
                        ?>
                            <!-- En-tête Entreprise -->
                            <div class="flex flex-col justify-between">
                                <div class="flex justify-between w-full">
                                    <div>
                                        <h1 class="text-xl font-bold">PACT</h1>
                                        <p>21 rue Case Nègres<br>97232, Fort-de-France<br>FR</p>
                                        
                                    </div>
                                    <div>
                                        
                                    </div>
                                </div>

                                <!-- Informations Client -->
                                <div class="flex justify-end">
                                    <div>
                                        <h1 class="text-xl font-bold"><?php echo htmlspecialchars($proDetails['nom_pro']); ?></h1>
                                        <p><?php echo htmlspecialchars($adresseDetails['numero']) . " " . htmlspecialchars($adresseDetails['odonyme']); ?><br><?php echo htmlspecialchars($adresseDetails['code_postal'])?><br>France</p>
                                        <br>
                                        <p>SIRET : <?php echo htmlspecialchars($proDetails['num_siren']) ?></p>
                                    </div>
                                    <br>
                                </div>
                            </div>

                            <hr>
                            

                            <!-- Informations Facture -->
                            <div class="mt-5">
                                <h1 class="text-2xl font-bold">Facture N° <?php echo htmlspecialchars($numero); ?></h1>
                                <p>Date d'émission : <?php echo htmlspecialchars($facture['date_emission']); ?></p>
                                <p>Règlement : Le premier de chaque mois </p>
                            </div>

                            <!-- Tableau de détails -->
                            <table class="w-full mt-5 border-collapse border border-gray-300">
                                <thead class="bg-blue-200">
                                    <tr>
                                        <th class="border p-2 text-left">Désignation</th>
                                        <th class="border p-2 text-right">Quantité</th>
                                        <th class="border p-2 text-right">Unité</th>
                                        <th class="border p-2 text-right">Prix Unitaire</th>
                                        <th class="border p-2 text-right">TVA</th>
                                        <th class="border p-2 text-right">Montant HT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $uniqueTypes = [];
                                    if (!in_array($typeOffre['nom'], $uniqueTypes)) {
                                        if ($typeOffre['nom']) {
                                            $unite = "jours";
                                        } else {
                                            $unite = "semaines";
                                        }
                                        $nbJoursEnLigne = 30; //valeur par défaut
                                        $uniqueTypes[] = $typeOffre['nom'];
                                        ?>
                                        <tr>
                                            <td class="border p-2"><?php echo htmlspecialchars($typeOffre['nom']); ?></td>
                                            <td class="border p-2 text-right"><?php echo $nbJoursEnLigne; ?></td>
                                            <td class="border p-2 text-right"><?php echo $unite ?></td>
                                            <td class="border p-2 text-right"><?php echo number_format($typeOffre['prix_ht'], 2); ?> €</td>
                                            <td class="border p-2 text-right"><?php echo $TVA ?>%</td>
                                            <td class="border p-2 text-right"><?php echo number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne; ?> €</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                        
                                </tbody>
                            </table>

                            <!-- Totaux -->
                            <div class="mt-5 flex justify-end">
                                <div class="w-1/3">
                                    <div class="flex justify-between">
                                        <span>Total HT</span>
                                        <span><?php echo number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne; ?> €</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>TVA (<?php echo $TVA ?>%)</span>
                                        <span> <?php echo (number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne) * ($TVA/100) ?> €</span>
                                    </div>
                                    <div class="flex justify-between font-bold">
                                        <span>Total TTC</span>
                                        <span><?php echo (number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne) * (1 + ($TVA/100)); ?> €</span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Mentions légales et coordonnées bancaires -->
                            <div class="mt-10 text-sm">
                                <p>En cas de retard de paiement, une pénalité de 3 fois le taux d’intérêt légal sera appliquée, à laquelle s’ajoutera une indemnité forfaitaire de 40 €.</p>
                                <p>PACT</p>
                            </div>

                            <!-- Footer -->
                            <div class="mt-5 text-center text-sm">
                                <p>SIRET : 123 456 789 00012</p>
                                <p>Page 1/1</p>
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

<!-- FOOTER -->
<?php
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/components/footer-pro.php';
?>

</body>

</html>