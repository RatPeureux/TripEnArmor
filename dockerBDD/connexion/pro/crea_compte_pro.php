<?php

include('../connect_params.php');

try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['titre']) && !empty($_POST['titre']) && isset($_POST['description']) && !empty($_POST['description'])) {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
    
            // Préparer la requête d'insertion
            $stmt = $dbh->prepare("INSERT INTO sae._organisation (id, nom, prenom, email, motdepasse, denomination) VALUES (:titre, :description, true, 1, 1, false, false)");
    
            // Lier les paramètres
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
    
            // Exécuter la requête
            if ($stmt->execute()) {
                echo "Offre créée avec succès!";
            } else {
                echo "Erreur lors de la création de l'offre.";
            }
        } else {
            echo "Veuillez remplir le champ Titre.";
        }
    }
} catch (\Throwable $e) {
    // Affiche une erreur en cas d'échec de la connexion à la base de données
    echo "Erreur !: " . $e->getMessage();
    die(); // Termine le script
}           

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un compte professionnel</title>
</head>
<body>
    
    <form action="" method="post">

        <h2>Dites nous en plus !</h2>

        <label for="organisme">Je suis un organisme </label>

        <select name="organisme" id="orga">
            <option value="Type">Type d'organisation</option>
            <option value="public">public</option>
            <option value="privé">privé</option>
        </select><br><br>

        <label for="denomination">Dénomination sociale*</label><br>
        <input type="text" name="denomination" id="denomination"><br><br>

        <label for="email">Adresse mail*</label><br>
        <input type="text" name="email" id="email"><br><br>
        <label for="adresse-postale">Adresse postale*</label><br>
        <input type="text" name="adresse-postale" id="adresse-postale"><br><br>
        <label for="code-postal">Code postal*</label><br>
        <input type="text" name="code-postal" id="code-postal"><br><br>
        <label for="ville">Ville*</label><br>
        <input type="text" name="ville" id="ville"><br><br>
        <label for="tel">Téléphone*</label><br>
        <input type="text" name="tel" id="tel"><br><br>



    </form>

</body>
</html>