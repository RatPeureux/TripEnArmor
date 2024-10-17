<?php
session_start(); // Démarre la session pour accéder aux variables de session
ob_start(); // Active la mise en mémoire tampon de sortie

include("../connect_params.php"); // Inclut le fichier de configuration pour la connexion à la base de données

// Vérifie si l'utilisateur est connecté et si le token est valide
if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
    header('Location: access_refuse_pro.php'); // Redirige vers la page d'accès refusé si l'utilisateur n'est pas connecté
    exit(); // Termine le script
}

try {
    // Établit la connexion à la base de données avec PDO
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configure PDO pour lancer des exceptions en cas d'erreur

    // Récupère le nom de l'utilisateur, ou 'Invité' s'il n'est pas défini
    $nom = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Invité';
    $offre = null; // Initialise la variable $offre

    // Prépare et exécute une requête pour récupérer une offre
    $stmt = $dbh->prepare("SELECT * FROM sae._offre LIMIT 1");
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données de l'offre

    // Vérifie si la requête est de type POST (formulaire soumis)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['mettre_en_ligne'])) {
            // Met à jour l'état de l'offre pour la mettre en ligne
            $stmt = $dbh->prepare("UPDATE sae._offre SET enligne = true WHERE idoffre = :idoffre");
            $stmt->execute(['idoffre' => $offre['idoffre']]);
        } elseif (isset($_POST['mettre_hors_ligne'])) {
            // Met à jour l'état de l'offre pour la mettre hors ligne
            $stmt = $dbh->prepare("UPDATE sae._offre SET enligne = false WHERE idoffre = :idoffre");
            $stmt->execute(['idoffre' => $offre['idoffre']]);
        }

        // Récupère à nouveau l'offre pour afficher l'état mis à jour
        $stmt = $dbh->prepare("SELECT * FROM sae._offre LIMIT 1");
        $stmt->execute();
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    // Affiche une erreur en cas de problème de connexion à la base de données
    echo "Erreur de connexion : " . $e->getMessage();
    exit(); // Termine le script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>Page Connectée</title> <!-- Titre de la page -->
</head>
<body>
    <!-- Affiche un message de bienvenue avec le nom de l'utilisateur, échappé pour éviter les attaques XSS -->
    <h1>Bonjour <?php echo htmlspecialchars($nom); ?>!</h1>
    <p>Bienvenue sur votre page de compte professionnel</p>

    <h2>Vos offres :</h2>

    <?php if ($offre): ?> <!-- Vérifie si une offre est disponible -->
        <h2>Détails de l'Offre</h2>
        <p><strong>Titre:</strong> <?php echo htmlspecialchars($offre['titre']); ?></p> <!-- Affiche le titre de l'offre -->
        <p><strong>Description:</strong> <?php echo htmlspecialchars($offre['description']); ?></p> <!-- Affiche la description de l'offre -->
        <p><strong>En ligne:</strong> <?php echo $offre['enligne'] ? 'Oui' : 'Non'; ?></p> <!-- Affiche si l'offre est en ligne -->
        <p><strong>Date de création:</strong> <?php echo htmlspecialchars($offre['create_date']); ?></p> <!-- Affiche la date de création -->

        <form action="" method="post"> <!-- Formulaire pour mettre à jour l'état de l'offre -->
            <?php if ($offre['enligne']): ?> <!-- Vérifie si l'offre est en ligne -->
                <input type="submit" name="mettre_hors_ligne" value="Mettre hors ligne"> <!-- Bouton pour mettre hors ligne -->
            <?php else: ?>
                <input type="submit" name="mettre_en_ligne" value="Mettre en ligne"> <!-- Bouton pour mettre en ligne -->
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>Aucune offre disponible.</p> <!-- Message si aucune offre n'est trouvée -->
    <?php endif; ?>

    <br> <!-- Espacement -->
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
    
    <!-- Lien pour se déconnecter -->
    <a href="logout_pro.php">Déconnexion</a>
</body>
</html>
