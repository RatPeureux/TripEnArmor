<?php
ob_start();
include('../connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

$message = ''; // Initialiser le message

// Partie pour traiter la soumission du second formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_tel'])) {
    // Assurer que tous les champs obligatoires sont remplis
    $adresse = $_POST['adresse'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $mdp = $_POST['mdp']; // Récupérer le mot de passe du champ caché
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $pseudo = $_POST['pseudo'];
    $tel = $_POST['num_tel'];

    // Hachage du mot de passe
    if (!empty($mdp)) { // Vérifier si $mdp n'est pas vide
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

        // Insérer dans la base de données
        $stmtAdresse = $dbh->prepare("INSERT INTO sae_db._adresse (adresse_postale, code_postal, ville) VALUES (:adresse, :code, :ville)");

        // Lier les paramètres pour l'adresse
        $stmtAdresse->bindParam(':ville', $ville);
        $stmtAdresse->bindParam(':adresse', $adresse);
        $stmtAdresse->bindParam(':code', $code);

        // Exécuter la requête pour l'adresse
        if ($stmtAdresse->execute()) {
            // Récupérer l'ID de l'adresse insérée
            $adresseId = $dbh->lastInsertId();

            // Préparer l'insertion dans la table Membre
            $stmtMembre = $dbh->prepare("INSERT INTO sae_db.Membre (email, mdp_hash, num_tel, adresse_id, pseudo, nom, prenom) VALUES (:mail, :mdp, :num_tel, :adresse_id, :pseudo, :nom, :prenom)");

            // Lier les paramètres pour le membre
            $stmtMembre->bindParam(':nom', $nom);
            $stmtMembre->bindParam(':prenom', $prenom);
            $stmtMembre->bindParam(':mail', $mail);
            $stmtMembre->bindParam(':mdp', $mdp_hache);
            $stmtMembre->bindParam(':pseudo', $pseudo);
            $stmtMembre->bindParam(':num_tel', $tel);
            $stmtMembre->bindParam(':adresse_id', $adresseId); // Utiliser l'ID de l'adresse

            // Exécuter la requête pour le membre
            if ($stmtMembre->execute()) {
                $message = "Votre compte a bien été créé. Vous allez maintenant être redirigé vers la page de connexion.";
            } else {
                $message = "Erreur lors de la création du compte : " . implode(", ", $stmtMembre->errorInfo());
            }
        } else {
            $message = "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresse->errorInfo());
        }
    } else {
        $message = "Mot de passe manquant.";
    }
}

ob_end_flush();
?>

<!-- Affichage du message dans le HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <script>
        // Fonction de redirection après un délai
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = "../../../pages/login-member.php";
            }, 5000); // 5000 ms = 5 secondes
        }
    </script>
</head>
<body>
    <h1>Création de Compte</h1>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <script>redirectToLogin();</script>
    <?php endif; ?>

    <!-- Formulaire de création de compte ici -->
</body>
</html>
