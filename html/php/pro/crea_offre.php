<?php
include('../connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Partie pour traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assurer que tous les champs obligatoires sont remplis
    $adresse = $_POST['adresse'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $description = $_POST['description'];
    $resume = $_POST['resume'];
    $titre = $_POST['titre'];
    $age = $_POST['age'];
    $prix = $_POST['prix'];

    // Vérifiez que tous les champs obligatoires sont remplis
    if ($adresse && $code && $ville && $description && $resume && $titre && $age) {
        // Insérer dans la base de données pour l'adresse
        $stmtAdresseOffre = $dbh->prepare("INSERT INTO sae_db.Adresse (adresse_postale, code_postal, ville) VALUES (:adresse, :code, :ville)");
        $stmtAdresseOffre->bindParam(':ville', $ville);
        $stmtAdresseOffre->bindParam(':adresse', $adresse);
        $stmtAdresseOffre->bindParam(':code', $code);

        if ($stmtAdresseOffre->execute()) {
            $adresseId = $dbh->lastInsertId();
            $dateCreation = date('Y-m-d H:i:s');

            // Préparer l'insertion dans la table Offre
            $stmtOffre = $dbh->prepare("INSERT INTO sae_db.Offre (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, adresse_id) VALUES (true, :description, :resume, :prix, :date_creation, null, null, :adresse_id)");
            $stmtOffre->bindParam(':description', $description);
            $stmtOffre->bindParam(':resume', $resume);
            $stmtOffre->bindParam(':adresse_id', $adresseId);
            $stmtOffre->bindParam(':date_creation', $dateCreation);
            $stmtOffre->bindParam(':prix', $prix);

            if ($stmtOffre->execute()) {
                $offreId = $dbh->lastInsertId();

                if ($stmtOffre->execute()) {
                    // Insérer dans la table Tarif_public
                    try {
                        $stmtTarifPublic = $dbh->prepare("INSERT INTO sae_db.Tarif_public (titre_tarif, age_min, age_max, offre_id) VALUES (:titre, :age_min, :age_max, :offre_id)");
                        $stmtTarifPublic->bindParam(':titre', $titre);
                        $stmtTarifPublic->bindParam(':age_min', $age);
                        $stmtTarifPublic->bindParam(':age_max', $age);
                        $stmtTarifPublic->bindParam(':offre_id', $offreId);

                        if ($stmtTarifPublic->execute()) {
                            echo json_encode(['success' => true]);
                            header("location: ../../../pages/accueil-pro.html");
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans la table Tarif_public : ' . implode(", ", $stmtTarifPublic->errorInfo())]);
                        }
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans la table Tarif_public : ' . $e->getMessage()]);
                    }
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la création de l\'offre : ' . implode(", ", $stmtOffre->errorInfo())]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion dans la table Adresse : ' . implode(", ", $stmtAdresseOffre->errorInfo())]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Tous les champs obligatoires doivent être remplis.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune soumission de formulaire détectée.']);
}
?>
