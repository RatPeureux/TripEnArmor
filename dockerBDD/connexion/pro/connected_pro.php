<?php
session_start();
ob_start();

include("../connect_params.php");

if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
    header('Location: access_refuse_pro.php');
    exit();
}

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Invité';
    $offre = null;

    $stmt = $dbh->prepare("SELECT * FROM sae._offre LIMIT 1");
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['mettre_en_ligne'])) {
            $stmt = $dbh->prepare("UPDATE sae._offre SET enligne = true WHERE idoffre = :idoffre");
            $stmt->execute(['idoffre' => $offre['idoffre']]);
        } elseif (isset($_POST['mettre_hors_ligne'])) {
            $stmt = $dbh->prepare("UPDATE sae._offre SET enligne = false WHERE idoffre = :idoffre");
            $stmt->execute(['idoffre' => $offre['idoffre']]);
        }

        
        $stmt = $dbh->prepare("SELECT * FROM sae._offre LIMIT 1");
        $stmt->execute();
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
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
    <h1>Bonjour <?php echo htmlspecialchars($nom); ?>!</h1>
    <p>Bienvenue sur votre page de compte professionnel</p>

    <h2>Vos offres :</h2>

    <?php if ($offre): ?>
        <h2>Détails de l'Offre</h2>
        <p><strong>Titre:</strong> <?php echo htmlspecialchars($offre['titre']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($offre['description']); ?></p>
        <p><strong>En ligne:</strong> <?php echo $offre['enligne'] ? 'Oui' : 'Non'; ?></p>
        <p><strong>Date de création:</strong> <?php echo htmlspecialchars($offre['create_date']); ?></p>

        <form action="" method="post">
            <?php if ($offre['enligne']): ?>
                <input type="submit" name="mettre_hors_ligne" value="Mettre hors ligne">
            <?php else: ?>
                <input type="submit" name="mettre_en_ligne" value="Mettre en ligne">
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>Aucune offre disponible.</p>
    <?php endif; ?>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    


    <a href="logout_pro.php">Déconnexion</a>
</body>
</html>
