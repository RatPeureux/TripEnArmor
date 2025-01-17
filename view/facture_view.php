<!-- 
    POUR UTILISER LA VUE, définir les variables suivantes avant de l'appeler
    $numero_facture
-->

<?php
// CHARGER LES INFORAMTIONS DE LA FACTURE
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// La facture
$stmt = $dbh->prepare("SELECT * FROM sae_db._facture WHERE numero_facture = ?");
$stmt->bindParam(1, $numero_facture);
$stmt->execute();
$facture = $stmt->fetch();

// Les différentes lignes de la facture
$id_offre = $facture['id_offre'];

$stmt = $dbh->prepare("SELECT * FROM sae_db._facture NATURAL JOIN sae_db._ligne_facture_en_ligne WHERE numero_facture = ?");
$stmt->bindParam(1, $numero_facture);
$stmt->execute();
$lignes_facture_en_ligne = $stmt->fetchAll();

// totaux pour les périodes
$HT_total_periodes = 0.00;
$TTC_total_periodes = 0.00;

$stmt = $dbh->prepare("SELECT * FROM sae_db._facture NATURAL JOIN sae_db._ligne_facture_option WHERE numero_facture = ?");
$stmt->bindParam(1, $numero_facture);
$stmt->execute();
$lignes_facture_option = $stmt->fetchAll();

// totaux pour les souscriptions
$HT_total_souscriptions = 0.00;
$TTC_total_souscriptions = 0.00;

// L'offre et le pro concerné
$stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE id_offre = :id_offre");
$stmt->bindParam(':id_offre', $id_offre);
$stmt->execute();
$offre = $stmt->fetch();

$id_pro = $offre['id_pro'];
$stmt = $dbh->prepare("SELECT * FROM sae_db._pro_prive WHERE id_compte = :id_pro");
$stmt->bindParam(':id_pro', $id_pro);
$stmt->execute();
$pro_details = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtAdresse = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE id_adresse = :id_adresse");
$stmtAdresse->bindParam(':id_adresse', $pro_details['id_adresse'], PDO::PARAM_INT);
$stmtAdresse->execute();
$adresse_details = $stmtAdresse->fetch(PDO::FETCH_ASSOC);

// Dates et numéro de facture
$date_emission = new DateTime($facture['date_emission']);
$date_emission = $date_emission->format('d/m/Y');

$date_echeance = new DateTime($facture['date_echeance']);
$date_echeance = $date_echeance->format('d/m/Y');
?>

<!-- FACTURE AVEC TOUS LES DETAILS -->
<div id="facture-details" class="bg-white border border-black p-5 flex flex-col mx-auto my-5 gap-5 max-w-4xl">

    <!-- En-tête -->
    <div class="flex flex-col justify-between">
        <!-- LA PACT -->
        <div class="flex justify-between w-full">
            <div>
                <h1 class="text-xl font-bold">PACT</h1>
                <p>2 Place de l'École, <br>29670, Henvic, Bretagne<br>FR</p>
            </div>
        </div>
        <!-- Informations du pro -->
        <div class="flex justify-end">
            <div>
                <h1 class="text-xl font-bold"><?php echo htmlspecialchars($pro_details['nom_pro']); ?></h1>
                <p><?php echo htmlspecialchars($adresse_details['numero']) . " " . htmlspecialchars($adresse_details['odonyme']); ?><br><?php echo htmlspecialchars($adresse_details['code_postal']) ?><br>France
                </p>
                <br>
                <p>SIRET : <?php echo htmlspecialchars($pro_details['num_siren']) ?></p>
            </div>
            <br>
        </div>
    </div>

    <hr>

    <!-- Informations Facture -->
    <div>
        <h1 class="text-2xl"><?php echo htmlspecialchars($offre['titre']) ?></h1>
        <br>
        <h1 class="text-xl">Facture N° <?php echo htmlspecialchars($numero_facture) ?></h1>
        <p>Date d'émission : <?php echo htmlspecialchars($date_emission) ?></p>
        <p>Règlement : Le <?php echo htmlspecialchars($date_echeance) ?> </p>
    </div>

    <!-- Détails pour les jours en ligne -->
    <div class="flex flex-col gap-2">
        <h2 class="text-xl">Jours en ligne</h2>

        <?php if (count($lignes_facture_en_ligne) > 0) { ?>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-blue-200">
                    <tr>
                        <th class="border p-2 text-center">Type</th>
                        <th class="border p-2 text-center">Du<p class="text-small"> (inclus)</p>
                        </th>
                        <th class="border p-2 text-center">Au<p class="text-small"> (inclus)</p>
                        </th>
                        <th class="border p-2 text-center">Qte</th>
                        <th class="border p-2 text-center">Unité</th>
                        <th class="border p-2 text-center">Prix uni. HT</th>
                        <th class="border p-2 text-center">Total HT</th>
                        <th class="border p-2 text-center">TVA</th>
                        <th class="border p-2 text-center">Prix uni. TTC</th>
                        <th class="border p-2 text-center">Total TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes_facture_en_ligne as $ligne_facture_en_ligne) {
                        $HT_total_periodes += $ligne_facture_en_ligne['prix_total_ht'];
                        $TTC_total_periodes += $ligne_facture_en_ligne['prix_total_ttc'];
                    ?>
                        <tr class="text-center">
                            <td class="border p-2 text-center">
                                <?php echo htmlspecialchars($ligne_facture_en_ligne['type_offre']); ?>
                            </td>

                            <td class="border p-2 text-center">
                                <?php echo DateTime::createFromFormat('Y-m-d', $ligne_facture_en_ligne['date_debut'])->format('d/m/y') ?>
                            </td>
                            <td class="border p-2 text-center">
                                <?php echo DateTime::createFromFormat('Y-m-d', $ligne_facture_en_ligne['date_fin'])->format('d/m/y') ?>
                            </td>

                            <td class="border p-2 text-center"><?php echo $ligne_facture_en_ligne['quantite'] ?></td>
                            <td class="border p-2 text-center"><?php echo $ligne_facture_en_ligne['unite'] ?></td>

                            <td class="border p-2 text-center"><?php echo $ligne_facture_en_ligne['prix_unitaire_ht'] ?> € </td>
                            <td class="border p-2 text-center"><?php echo $ligne_facture_en_ligne['prix_total_ht'] ?> €</td>

                            <!-- TVA -->
                            <td class="border p-2 text-center"><?php echo $ligne_facture_en_ligne['tva'] ?>%</td>

                            <td class="border p-2 text-center"><?php echo $ligne_facture_en_ligne['prix_unitaire_ttc'] ?> €</td>
                            <td class="border p-2 text-center">
                                <?php echo $ligne_facture_en_ligne['prix_total_ttc'] ?> €
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Aucun jour en ligne</p>
        <?php } ?>
    </div>

    <!-- Détails des options -->
    <div class="flex flex-col gap-2">
        <h2 class="text-xl">Options</h2>

        <?php if (count($lignes_facture_option) > 0) { ?>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-blue-200">
                    <tr class="text-center">
                        <th class="border p-2 text-center">Nom</th>
                        <th class="border p-2 text-center">Du<p class="text-small"> (inclus)</p>
                        </th>
                        <th class="border p-2 text-center">Au<p class="text-small"> (inclus)</p>
                        </th>
                        <th class="border p-2 text-center">Qte</th>
                        <th class="border p-2 text-center">Unité</th>
                        <th class="border p-2 text-center">Prix uni. HT</th>
                        <th class="border p-2 text-center">Total HT</th>
                        <th class="border p-2 text-center">TVA</th>
                        <th class="border p-2 text-center">Prix uni. TTC</th>
                        <th class="border p-2 text-center">Total TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes_facture_option as $ligne_facture_option) {
                        $HT_total_souscriptions += $ligne_facture_option['prix_total_ht'];
                        $TTC_total_souscriptions += $ligne_facture_option['prix_total_ttc'];
                    ?>
                        <tr>
                            <td class="border p-2 text-center">
                                <?php echo htmlspecialchars($ligne_facture_option['nom_option']); ?>
                            </td>

                            <td class="border p-2 text-center">
                                <?php echo DateTime::createFromFormat('Y-m-d', $ligne_facture_option['date_debut'])->format('d/m/y') ?>
                            </td>
                            <td class="border p-2 text-center">
                                <?php echo DateTime::createFromFormat('Y-m-d', $ligne_facture_option['date_fin'])->format('d/m/y') ?>
                            </td>

                            <td class="border p-2 text-center">
                                <?php echo htmlspecialchars($ligne_facture_option['quantite']); ?>
                            </td>
                            <td class="border p-2 text-center"><?php echo $ligne_facture_option['unite'] ?></td>

                            <td class="border p-2 text-center"><?php echo $ligne_facture_option['prix_unitaire_ht'] ?> €</td>
                            <td class="border p-2 text-center"><?php echo $ligne_facture_option['prix_total_ht'] ?> €</td>

                            <td class="border p-2 text-center"><?php echo $ligne_facture_option['tva'] ?>%</td>

                            <td class="border p-2 text-center"><?php echo $ligne_facture_option['prix_unitaire_ttc'] ?> €</td>
                            <td class="border p-2 text-center"><?php echo $ligne_facture_option['prix_total_ttc'] ?> €</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Aucune souscription</p>
        <?php } ?>
    </div>

    <!-- Totaux globaux -->
    <div class="flex justify-end">
        <div class="w-1/3">
            <div class="flex justify-between">
                <span>Total HT</span>
                <span><?php echo number_format($HT_total_periodes + $HT_total_souscriptions, 2, ',', ' ') ?> €</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Total TTC</span>
                <span><?php echo number_format($TTC_total_periodes + $TTC_total_souscriptions, 2, ',', ' ') ?>
                    €</span>
            </div>
        </div>
    </div>

    <hr>

    <!-- Mentions légales et coordonnées bancaires -->
    <div class="mt-10 text-sm text-center">
        <p>En cas de retard de paiement, une pénalité de 3 fois le taux d’intérêt légal sera appliquée, à
            laquelle s’ajoutera une indemnité forfaitaire de 40€.</p>
        <p>PACT</p>
    </div>

    <!-- Footer -->
    <div class="text-center text-sm">
        <p>Page 1/1</p>
    </div>
</div>