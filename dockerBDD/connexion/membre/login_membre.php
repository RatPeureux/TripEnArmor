<?php
include('../connect_params.php');
session_start();

$error = "";

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $connexion = $_POST['connexion'];
        $mdp = $_POST['mdp'];

        
        $stmt = $dbh->prepare("SELECT * FROM sae._membre WHERE email = :connexion OR pseudo = :connexion");
        $stmt->bindParam(':connexion', $connexion);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        if ($stmt->errorInfo()[0] !== '00000') {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
        }

        
        if ($user && $user['motdepasse'] === $mdp) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['pseudo'];
            $_SESSION['token'] = bin2hex(random_bytes(32));

            header('Location: connected_membre.php?token=' . $_SESSION['token']);
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage();
    die();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/output.css">
    <title>Connexion à la PACT</title>
</head>
<body>

    <?php if (!empty($error)): ?>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <img src="../public/icones/back.svg" alt="retour">
    <img src="../public/images/logo.svg" alt="moine">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>J'ai un compte Membre !</h3>

        <label for="connexion">Identifiant*</label>
        <input type="text" id="connexion" name="connexion" required>

        <label for="mdp">Mot de passe*</label>
        <input type="password" id="mdp" name="mdp" required>

        <input type="submit" value="Me connecter">
        <div>
            <input type="button" value="Mot de passe oublié ?">
            <input type="button" value="Créer un compte">
        </div>
    </form>
</body>
</html>
