<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Requêtes GET nécessaires pour pouvoir fonctioner avec AJAX
if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];

    // Requête SQL retournant les informations de la facture
    $stmt = $dbh->prepare("SELECT * FROM sae_db.vue_periodes_en_ligne WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->execute();
    $preview_loaded = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print_r($preview_loaded);
} else {
    echo 'Erreur lors du chargement des montants';
} ?>

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

$stmtTypeOffre = $dbh->prepare("SELECT * FROM sae_db._type_offre WHERE id_type_offre = :id_type_offre");
$stmtTypeOffre->bindParam(':id_type_offre', $offre['id_type_offre'], PDO::PARAM_INT);
$stmtTypeOffre->execute();
$typeOffre = $stmtTypeOffre->fetch(PDO::FETCH_ASSOC);
?>

<!-- En-tête Entreprise -->
<div class="flex flex-col justify-between">
    <div class="flex justify-between w-full">
        <div>
            <h1 class="text-xl font-bold">PACT</h1>
            <p>21 rue Case Nègres<br>97232, Fort-de-France<br>FR</p>
        </div>
    </div>

    <!-- Informations Client -->
    <div class="flex justify-end">
        <div>
            <h1 class="text-xl font-bold"><?php echo htmlspecialchars($proDetails['nom_pro']); ?></h1>
            <p><?php echo htmlspecialchars($adresseDetails['numero']) . " " . htmlspecialchars($adresseDetails['odonyme']); ?><br><?php echo htmlspecialchars($adresseDetails['code_postal']) ?><br>France
            </p>
            <br>
            <p>SIRET : <?php echo htmlspecialchars($proDetails['num_siren']) ?></p>
        </div>
        <br>
    </div>
</div>

<hr>

<!-- Informations Facture -->
<div class="mt-5">
    <h1 class="text-2xl"><?php echo htmlspecialchars($offre['titre']) ?></p>
        <br>
        <h1 class="text-xl">Facture N° <?php echo htmlspecialchars($numero); ?></h1>
        <p>Date d'émission : <?php echo htmlspecialchars($date_emission); ?></p>
        <p>Règlement : Le premier jour de chaque mois </p>
</div>

<!-- Tableau de détails -->
<table class="w-full mt-5 border-collapse border border-gray-300">
    <thead class="bg-blue-200">
        <tr>
            <th class="border p-2 text-left">Désignation</th>
            <th class="border p-2 text-right">Quantité</th>
            <th class="border p-2 text-right">Unité</th>
            <th class="border p-2 text-right">Prix Unitaire HT</th>
            <th class="border p-2 text-right">Total HT</th>
            <th class="border p-2 text-right">TVA</th>
            <th class="border p-2 text-right">Total TTC</th>
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
            $nbJoursEnLigne = 30;
            $uniqueTypes[] = $typeOffre['nom'];
            ?>
            <tr>
                <td class="border p-2"><?php echo htmlspecialchars($typeOffre['nom']); ?></td>
                <td class="border p-2 text-right"><?php echo $nbJoursEnLigne; ?></td>
                <td class="border p-2 text-right"><?php echo $unite ?></td>
                <td class="border p-2 text-right"><?php echo number_format($typeOffre['prix_ht'], 2); ?>€
                </td>
                <td class="border p-2 text-right">
                    <?php echo number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne; ?>€
                </td>
                <td class="border p-2 text-right"><?php echo $TVA ?>%</td>
                <td class="border p-2 text-right">
                    <?php echo number_format($typeOffre['prix_ttc'], 2) * $nbJoursEnLigne ?>€</td>

            </tr>
            <?php
        }
        ?>

    </tbody>
</table>

<!-- Totaux globaux -->
<div class="mt-5 flex justify-end">
    <div class="w-1/3">
        <div class="flex justify-between">
            <span>Total HT</span>
            <span><?php echo number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne; ?>€</span>
        </div>
        <div class="flex justify-between">
            <span>TVA (<?php echo $TVA ?>%)</span>
            <span>
                <?php echo (number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne) * ($TVA / 100) ?>€</span>
        </div>
        <div class="flex justify-between font-bold">
            <span>Total TTC</span>
            <span><?php echo (number_format($typeOffre['prix_ht'], 2) * $nbJoursEnLigne) * (1 + ($TVA / 100)); ?>€</span>
        </div>
    </div>
</div>

<hr>

<!-- Mentions légales et coordonnées bancaires -->
<div class="mt-10 text-sm">
    <p>En cas de retard de paiement, une pénalité de 3 fois le taux d’intérêt légal sera appliquée, à
        laquelle s’ajoutera une indemnité forfaitaire de 40€.</p>
    <p>PACT</p>
</div>

<!-- Footer -->
<div class="mt-5 text-center text-sm">
    <p>SIRET : 123 456 789 00012</p>
    <p>Page 1/1</p>
</div>
</div>
