<?php
include('../connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Définit un tableau d'options pour le type d'offre
$options = [
    'stjxd' => 'Gratuite',
    'Standard' => 'Standard',
    'Premium' => 'Premium',
];

// Définit un tableau de tags pour classifier les offres
$tag = [
    'Tag1' => 'Activite',
    'Tag2' => 'Visite',
    'Tag3' => 'Spectacle',
    'Tag4' => 'Parc d attraction',
    'Tag5' => 'Restauration'
];

// Partie pour traiter la soumission du second formulaire
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
        // Vérifiez que $dbh est initialisé
        if (isset($dbh)) {
            // Insérer dans la base de données pour l'adresse
            $stmtAdresseOffre = $dbh->prepare("INSERT INTO sae_db.Adresse (adresse_postale, code_postal, ville) VALUES (:adresse, :code, :ville)");
            $stmtAdresseOffre->bindParam(':ville', $ville);
            $stmtAdresseOffre->bindParam(':adresse', $adresse);
            $stmtAdresseOffre->bindParam(':code', $code);

            if ($stmtAdresseOffre->execute()) {
                $adresseId = $dbh->lastInsertId();

                // Récupérer la date de création
                $dateCreation = date('Y-m-d H:i:s'); // Format de date MySQL

                // Préparer l'insertion dans la table Offre
                $stmtOffre = $dbh->prepare("INSERT INTO sae_db.Offre (est_en_ligne, description_offre, resume_offre, prix_mini, date_creation, date_mise_a_jour, date_suppression, adresse_id) VALUES (true, :description, :resume, :prix, :date_creation, null, null, :adresse_id)");
                $stmtOffre->bindParam(':description', $description);
                $stmtOffre->bindParam(':resume', $resume);
                $stmtOffre->bindParam(':adresse_id', $adresseId);
                $stmtOffre->bindParam(':date_creation', $dateCreation);
                $stmtOffre->bindParam(':prix', $prix);

                // Exécuter la requête pour le professionnel
                if ($stmtOffre->execute()) {
                    // Insérer dans la table Tarif_public
                    try {
                        $offreId = $dbh->lastInsertId();
                        $stmtTarifPublic = $dbh->prepare("INSERT INTO sae_db.Tarif_public (titre_tarif, age_min, age_max, offre_id) VALUES (:titre, :age_min, :age_max, :offre_id)");
                        $stmtTarifPublic->bindParam(':titre', $titre);
                        $stmtTarifPublic->bindParam(':age_min', $age);
                        $stmtTarifPublic->bindParam(':age_max', $age);
                        $stmtTarifPublic->bindParam(':offre_id', $offreId);

                        if ($stmtTarifPublic->execute()) {
                            $message = "Votre compte a bien été créé.";
                            header("location: ../../../pages/accueil-pro.html");
                        } else {
                            $message = "Erreur lors de l'insertion dans la table Tarif_public : " . implode(", ", $stmtTarifPublic->errorInfo());
                        }
                    } catch (Exception $e) {
                        $message = "Erreur lors de l'insertion dans la table Tarif_public : " . $e->getMessage();
                    }
                } else {
                    $message = "Erreur lors de la création de l'offre : " . implode(", ", $stmtOffre->errorInfo());
                }
            } else {
                $message = "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresseOffre->errorInfo());
            }
        } else {
            $message = "Erreur de connexion à la base de données.";
        }
    } else {
        $message = "Tous les champs obligatoires doivent être remplis.";
    }
} else {
    $message = "Aucune soumission de formulaire détectée.";
}

// Affichage du message d'erreur
if (isset($message)) {
    echo '<div style="color: red;">' . htmlspecialchars($message) . '</div>';
}

ob_end_flush();
?>
