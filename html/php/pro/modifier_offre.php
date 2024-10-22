<?php
ob_start();
include('/php/connect_params.php');

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'ID de l'offre est passé et est un entier
    if (isset($_GET['offre-id']) && is_numeric($_GET['offre-id'])) {
        $offreId = (int) $_GET['offre-id'];
    } else {
        die("ID d'offre invalide.");
    }

    $sql = "SELECT o.*, a.adresse_postale, a.code_postal, a.ville 
            FROM sae_db.Offre o 
            JOIN sae_db.Adresse a ON o.adresse_id = a.adresse_id 
            WHERE o.offre_id = :offre_id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT); // Spécifiez le type pour être sûr
    $stmt->execute();

    $offre = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifiez que l'offre existe
    if (!$offre) {
        die("Offre non trouvée.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
        $adresse = isset($_POST['adresse']) ? $_POST['adresse'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $ville = isset($_POST['ville']) ? $_POST['ville'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $resume = isset($_POST['resume']) ? $_POST['resume'] : '';
        $age = isset($_POST['age']) ? $_POST['age'] : '';
        $prix = isset($_POST['prix']) ? $_POST['prix'] : '';

        if ($adresse && $code && $ville && $description && $resume && $titre && $age) {
            // Mettre à jour l'adresse
            $stmtAdresseOffre = $dbh->prepare("UPDATE sae_db.Adresse 
                SET adresse_postale = :adresse, code_postal = :code, ville = :ville 
                WHERE adresse_id = (SELECT adresse_id FROM sae_db.Offre WHERE offre_id = :offreId)
            ");
            $stmtAdresseOffre->bindParam(':ville', $ville);
            $stmtAdresseOffre->bindParam(':adresse', $adresse);
            $stmtAdresseOffre->bindParam(':code', $code);
            $stmtAdresseOffre->bindParam(':offreId', $offreId);

            if ($stmtAdresseOffre->execute()) {
                $dateMiseAJour = date('Y-m-d H:i:s');

                // Mettre à jour l'offre
                $stmtOffre = $dbh->prepare("UPDATE sae_db.Offre 
                    SET description_offre = :description, resume_offre = :resume, prix_mini = :prix, date_mise_a_jour = :date_mise_a_jour 
                    WHERE offre_id = :offreId
                ");
                $stmtOffre->bindParam(':description', $description);
                $stmtOffre->bindParam(':resume', $resume);
                $stmtOffre->bindParam(':offreId', $offreId);
                $stmtOffre->bindParam(':date_mise_a_jour', $dateMiseAJour);
                $stmtOffre->bindParam(':prix', $prix);

                if ($stmtOffre->execute()) {
                    $stmtTarifPublic = $dbh->prepare("UPDATE sae_db.Tarif_Public 
                        SET titre_tarif = :titre, age_min = :age_min, age_max = :age_max 
                        WHERE offre_id = :offre_id
                    ");
                    $stmtTarifPublic->bindParam(':titre', $titre);
                    $stmtTarifPublic->bindParam(':age_min', $age);
                    $stmtTarifPublic->bindParam(':age_max', $age);
                    $stmtTarifPublic->bindParam(':offre_id', $offreId);

                    if ($stmtTarifPublic->execute()) {
                        header("Location: /pages/accueil-pro.html");
                        exit;
                    } else {
                        echo "Erreur lors de la mise à jour dans la table Tarif_Public : " . implode(", ", $stmtTarifPublic->errorInfo());
                    }
                } else {
                    echo "Erreur lors de la mise à jour de l'offre : " . implode(", ", $stmtOffre->errorInfo());
                }
            } else {
                echo "Erreur lors de la mise à jour de l'adresse : " . implode(", ", $stmtAdresseOffre->errorInfo());
            }
        } else {
            echo "Tous les champs obligatoires doivent être remplis.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Offre</title>
</head>
<body>
    <h1>Modifier l'Offre</h1>
    <form method="POST" action="">
        <label for="adresse">Adresse:</label>
        <input type="text" name="adresse" value="<?php echo htmlspecialchars($offre['adresse_postale'] ?? '', ENT_QUOTES); ?>" required>
        
        <label for="code">Code Postal:</label>
        <input type="text" name="code" value="<?php echo htmlspecialchars($offre['code_postal'] ?? '', ENT_QUOTES); ?>" required>
        
        <label for="ville">Ville:</label>
        <input type="text" name="ville" value="<?php echo htmlspecialchars($offre['ville'] ?? '', ENT_QUOTES); ?>" required>
        
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($offre['description_offre'] ?? '', ENT_QUOTES); ?></textarea>
        
        <label for="resume">Résumé:</label>
        <input type="text" name="resume" value="<?php echo htmlspecialchars($offre['resume_offre'] ?? '', ENT_QUOTES); ?>" required>
        
        <label for="titre">Titre:</label>
        <input type="text" name="titre" value="<?php echo htmlspecialchars($offre['titre_tarif'] ?? '', ENT_QUOTES); ?>" required>
        
        <label for="age">Âge:</label>
        <input type="number" name="age" value="<?php echo htmlspecialchars($age ?? '', ENT_QUOTES); ?>" required>
        
        <label for="prix">Prix:</label>
        <input type="number" name="prix" value="<?php echo htmlspecialchars($offre['prix_mini'] ?? '', ENT_QUOTES); ?>" required>
        
        <input type="submit" value="Modifier l'offre">
    </form>
</body>
</html>
