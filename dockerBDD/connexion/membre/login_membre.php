<?php
include('../connect_params.php'); // Inclut le fichier de configuration pour la connexion à la base de données

session_start(); // Démarre une session pour gérer l'authentification

$error = ""; // Initialise une variable pour stocker les messages d'erreur

try {
    // Création d'une nouvelle instance PDO pour se connecter à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configure PDO pour lancer des exceptions en cas d'erreur

    // Vérifie si la requête est de type POST (formulaire soumis)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $connexion = $_POST['connexion']; // Récupère l'identifiant (email ou pseudo)
        $mdp = $_POST['mdp']; // Récupère le mot de passe

        // Prépare une requête SQL pour rechercher l'utilisateur par email ou pseudo
        $stmt = $dbh->prepare("SELECT * FROM sae._membre WHERE email = :connexion OR pseudo = :connexion");
        $stmt->bindParam(':connexion', $connexion); // Lie le paramètre
        $stmt->execute(); // Exécute la requête

        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données de l'utilisateur

        // Vérifie s'il y a une erreur dans la requête SQL
        if ($stmt->errorInfo()[0] !== '00000') {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true)); // Enregistre l'erreur dans les logs
        }

        // Vérifie si l'utilisateur a été trouvé et si le mot de passe est correct
        if ($user && password_verify($mdp, $user['motdepasse'])) {
            // Stocke les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['pseudo'];
            $_SESSION['token'] = bin2hex(random_bytes(32)); // Génère un token pour la sécurité

            // Redirige vers la page connectée avec le token en paramètre
            header('Location: connected_membre.php?token=' . $_SESSION['token']);
            exit(); // Termine le script
        } else {
            $error = "Email ou mot de passe incorrect."; // Définit un message d'erreur si les identifiants sont incorrects
        }
    }
} catch (PDOException $e) {
    // Affiche une erreur en cas d'échec de la connexion à la base de données
    echo "Erreur !: " . $e->getMessage();
    die(); // Termine le script
}
?>

<!DOCTYPE html>
<html lang="fr"> <!-- Déclare la langue du document -->
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <link rel="icon" type="image" href="../../../public/images/favicon.png"> <!-- Favicon de la page -->
    <link rel="stylesheet" href="../../../styles/output.css"> <!-- Lien vers la feuille de style -->
    <title>Connexion à la PACT</title> <!-- Titre de la page -->
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script> <!-- Chargement des icônes Font Awesome -->
</head>
<body class="h-screen bg-base100 p-4 overflow-hidden"> <!-- Styles pour le corps de la page -->
    <?php if (!empty($error)): ?> <!-- Vérifie si une erreur existe -->
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div> <!-- Affiche le message d'erreur, échappé pour éviter XSS -->
    <?php endif; ?>
    <i class="fa-solid fa-arrow-left fa-2xl"></i> <!-- Icône de retour -->
    <div class="h-full flex flex-col items-center justify-center"> <!-- Conteneur centré -->
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto"> <!-- Conteneur pour le formulaire -->
            <img class="absolute -top-24" src="../../../public/images/logo.svg" alt="moine" width="108"> <!-- Logo -->
            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="" method="post" enctype="multipart/form-data"> <!-- Formulaire -->
                <p class="pb-3">J'ai un compte Membre</p> <!-- Message d'introduction -->
                
                <label class="text-small" for="connexion">Identifiant*</label> <!-- Label pour l'identifiant -->
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="connexion" name="connexion" required> <!-- Champ pour l'identifiant -->
                
                <label class="text-small" for="mdp">Mot de passe*</label> <!-- Label pour le mot de passe -->
                <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="mdp" name="mdp" required> <!-- Champ pour le mot de passe -->

                <input class="bg-primary text-white font-bold w-full h-12 mb-1.5 rounded-lg" type="submit" value="Me connecter"> <!-- Bouton de soumission -->
                <div class="flex flex-nowrap h-12 space-x-1.5"> <!-- Conteneur pour les liens -->
                    <input class="text-primary text-small w-full h-full p-1 rounded-lg border border-primary text-wrap" type="button" value="Mot de passe oublié ?"> <!-- Bouton pour le mot de passe oublié -->
                    <a class="w-full" href="crea_compte_membre.php"> <!-- Lien vers la création de compte -->
                        <input class="text-primary text-small w-full h-full p-1 rounded-lg border border-primary text-wrap" type="button" value="Créer un compte"> <!-- Bouton pour créer un compte -->
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
