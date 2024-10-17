<?php
session_start(); // Démarre la session pour pouvoir stocker des informations sur l'utilisateur
include('../connect_params.php'); // Inclut le fichier de paramètres de connexion à la base de données

$error = ""; // Variable pour stocker les messages d'erreur

try {
    // Connexion à la base de données avec PDO
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

    // Vérifie si la requête est une soumission de formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['connnexion']; // Récupère l'email ou le nom soumis
        $mdp = $_POST['mdp']; // Récupère le mot de passe soumis

        // Prépare une requête SQL pour trouver l'utilisateur par email ou nom
        $stmt = $dbh->prepare("SELECT * FROM sae._organisation WHERE email = :connnexion OR nom = :connnexion");
        $stmt->bindParam(':connnexion', $email); // Lie le paramètre à la valeur de l'email
        $stmt->execute(); // Exécute la requête

        // Vérifie s'il y a une erreur SQL
        if ($stmt->errorInfo()[0] !== '00000') {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true)); // Log l'erreur
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données de l'utilisateur
        error_log(print_r($user, true)); // Log les données de l'utilisateur pour débogage
        
        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if ($user && $user['motdepasse'] === $mdp) {
            // Stocke les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['token'] = bin2hex(random_bytes(32)); // Génère un token de session
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['prenom'];
            header('Location: connected_pro.php?token=' . $_SESSION['token']); // Redirige vers la page connectée
            exit();
        } else {
            $error = "Email ou mot de passe incorrect"; // Message d'erreur si les identifiants ne sont pas valides
        }
    }
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage(); // Affiche une erreur si la connexion échoue
    die(); // Arrête l'exécution du script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>Page de Connexion Membre</title> <!-- Titre de la page -->
</head>
<body>

<?php if (!empty($error)): ?> <!-- Vérifie s'il y a un message d'erreur -->
    <div style="color: red;"><?php echo htmlspecialchars($error); ?></div> <!-- Affiche le message d'erreur -->
<?php endif; ?>

<form action="" method="post"> <!-- Formulaire pour la connexion -->
    <label for="connnexion">Email ou nom :</label>
    <input type="text" name="connnexion" id="connnexion" required> <!-- Champ pour l'email ou nom -->

    <br>

    <label for="mdp">Mot de passe :</label>
    <input type="password" name="mdp" id="mdp" required> <!-- Champ pour le mot de passe -->

    <br>

    <input type="submit" value="Se connecter"> <!-- Bouton de soumission -->
</form>

</body>
</html>
