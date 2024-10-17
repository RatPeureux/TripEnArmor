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
    <link rel="icon" type="image" href="../../../public/images/favicon.png">
    <link rel="stylesheet" href="../../../styles/output.css">
    <title>Connexion à la PACT</title>
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
</head>
<body class="h-screen bg-base100 p-4 overflow-hidden">
    <?php if (!empty($error)): ?>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <i class="fa-solid fa-arrow-left fa-2xl"></i>
    <div class="h-full flex flex-col items-center justify-center">
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
            <img class="absolute -top-24" src="../../../public/images/logo.svg" alt="moine" width="108">
            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="" method="post" enctype="multipart/form-data">
                <p class="pb-3">J'ai un compte Membre</p>
                
                <label class="text-small" for="connexion">Identifiant*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="connexion" name="connexion" title="" required>
                
                <label class="text-small" for="passwd">Mot de passe*</label>
                <input class="p-2 bg-base100 w-full h-12 mb-3 rounded-lg" type="password" id="mdp" name="mdp" title="" required>

                <input class="bg-primary text-white font-bold w-full h-12 mb-1.5 rounded-lg" type="submit" value="Me connecter">
                <div class="flex flex-nowrap h-12 space-x-1.5">
                    <input class="text-primary text-small w-full h-full p-1 rounded-lg border border-primary text-wrap" type="button" value="Mot de passe oublié ?">
                    <a class="w-full" href="create-member1.html">
                        <input class="text-primary text-small w-full h-full p-1 rounded-lg border border-primary text-wrap" type="button" value="Créer un compte">
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
