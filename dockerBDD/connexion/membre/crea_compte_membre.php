<?php

include('../connect_params.php');

try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['prenom']) && !empty($_POST['prenom']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['mdp']) && !empty($_POST['mdp']) && isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $mdp = $_POST['mdp'];
            $email = $_POST['email'];
            $pseudo = $_POST['pseudo'];
            $stmt = $dbh->prepare("INSERT INTO sae._membre (nom, prenom, email, motdepasse, pseudo) VALUES (:nom, :prenom, :email, :mdp, :pseudo)");
    
            // Lier les paramètres
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mdp', $mdp);
    
            // Exécuter la requête
            if ($stmt->execute()) {
                echo "Compte créée avec succès!";
                
            } else {
                echo "Erreur lors de la création de l'offre.";
            }
        } else {
            echo "Veuillez remplir les champs.";
        }
    }
} catch (\Throwable $e) {
    // Affiche une erreur en cas d'échec de la connexion à la base de données
    echo "Erreur !: " . $e->getMessage();
    die(); // Termine le script
}           

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../../../public/images/favicon.png">
    <link rel="stylesheet" href="../../../styles/output.css">
    <title>Création de compte PACT 1/2</title>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
</head>
<body class="h-screen bg-base100 p-4 overflow-hidden">
    <a href="login_membre.php" class="fa-solid fa-arrow-left fa-2xl"></a>
    <div class="h-full flex flex-col items-center justify-center">
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
            <img class="absolute -top-24" src="../../../public/images/logo.svg" alt="moine" width="108">
            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="" method="post" enctype="multipart/form-data">
                <p class="pb-3">Je crée un compte Membre</p>

                <div class="flex flex-nowrap space-x-3 mb-1.5">
                    <div class="w-full">
                        <label class="text-small" for="prenom">Prénom*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="prenom" name="prenom" pattern="" title="" required>
                    </div>
                    <div class="w-full">
                        <label class="text-small" for="nom">Nom*</label>
                        <input class="p-2 bg-base100 w-full h-12 rounded-lg" type="text" id="nom" name="nom" pattern="" title="" required>
                    </div>
                </div>
                
                <label class="text-small" for="id">Adresse mail*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="email" name="email" pattern="" title="" required>
                
                <label class="text-small" for="passwd">Mot de passe*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="mdp" name="mdp" pattern="" title="" required>

                <label class="text-small" for="passwd-conf">Confirmer le mot de passe*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="passwd-conf" name="passwd-conf" pattern="" title="" required>

                <input class="bg-primary text-white font-bold w-full h-12 mb-1.5 rounded-lg" type="submit" value="Continuer">
                <a href="login_membre.html">
                    <input class="text-primary w-full h-12 p-1 rounded-lg border border-primary text-wrap" type="button" value="J'ai déjà un compte">
                </a>
            </form>
        </div>
    </div>
</body>
</html>