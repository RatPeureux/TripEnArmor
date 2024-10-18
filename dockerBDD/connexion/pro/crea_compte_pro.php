<?php
ob_start();
include('../connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

// Partie pour traiter la soumission du second formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tel'])) {
    // Assurer que tous les champs obligatoires sont remplis
    $adresse = $_POST['adresse'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $tel = $_POST['tel'];
    $mdp = $_POST['mdp']; // Récupérer le mot de passe du champ caché
    $mail = $_POST['mail'];

    // Hachage du mot de passe
    if (!empty($mdp)) { // Vérifier si $mdp n'est pas vide
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

        // Insérer dans la base de données
        $stmt = $dbh->prepare("INSERT INTO sae._organisation (nom, prenom, email, motdepasse, denomination) VALUES ('fzuheg', 'salam', :mail, :mdp, :denomination)");

        // Lier les paramètres
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':mdp', $mdp_hache);
        $stmt->bindParam(':denomination', $denomination);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Compte créé avec succès!";
        } else {
            echo "Erreur lors de la création du compte : " . implode(", ", $stmt->errorInfo());
        }
    } else {
        echo "Mot de passe manquant.";
    }
}   

header("location: ../../../pages/login-pro.html");

ob_end_flush();

?>