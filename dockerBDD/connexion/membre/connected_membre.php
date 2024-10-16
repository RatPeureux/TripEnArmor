<?php
session_start();
ob_start();


if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
    header('Location: access_refuse_membre.php');
    
    exit();
}

$pseudo = isset($_SESSION['user_pseudo']) ? $_SESSION['user_pseudo'] : 'Invité';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Connectée</title>
</head>
<body>
    <h1>Bonjour <?php echo htmlspecialchars($pseudo); ?>!</h1>
    <p>Bienvenue sur votre page de compte membre</p>
    <a href="logout_membre.php">Déconnexion</a>
</body>
</html>

