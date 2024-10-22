<?php
session_start();
include('../connect_params.php'); // Inclut le fichier de paramètres de connexion à la base de données

$error = ""; // Variable pour stocker les messages d'erreur

try {
    // Connexion à la base de données avec PDO
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

    // Vérifie si la requête est une soumission de formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['id']; // Récupère l'email ou le nom soumis
        $mdp = $_POST['mdp']; // Récupère le mot de passe soumis

        // Prépare une requête SQL pour trouver l'utilisateur par email ou nom
        $stmt = $dbh->prepare("SELECT * FROM sae_db.Membre WHERE email = :id OR pseudo = :id");
        $stmt->bindParam(':id', $email); // Lie le paramètre à la valeur de l'email
        $stmt->execute(); // Exécute la requête

        // Vérifie s'il y a une erreur SQL
        if ($stmt->errorInfo()[0] !== '00000') {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true)); // Log l'erreur
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données de l'utilisateur
        error_log(print_r($user, true)); // Log les données de l'utilisateur pour débogage
        
        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($mdp, $user['mdp_hash'])) {
            // Stocke les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id_compte'];
            $_SESSION['token'] = bin2hex(random_bytes(32)); // Génère un token de session
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['prenom'];
            header('location: /?token=' . $_SESSION['token']); // Redirige vers la page connectée
            exit();
        } else {
            $error = "Email ou mot de passe incorrect"; // Message d'erreur si les identifiants ne sont pas valides
        }
    }
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage(); // Affiche une erreur si la connexion échoue
    die(); // Arrête l'exécution du script
}

header("location: /");

?>