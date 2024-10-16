<?php
session_start();
ob_start();


if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
    header('Location: access_refuse_pro.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Connectée</title>
</head>
<body>
    <h1>HELLO !</h1>
    <p>Bienvenue sur votre page de compte professionnel.</p>
    <a href="logout_pro.php">Déconnexion</a>
</body>
</html>

