<?php if (!isset($_POST['id'])) { ?>
    
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers le favicon de l'application -->
    <link rel="icon" type="image" href="../public/images/favicon.png">
    <!-- Lien vers le fichier CSS pour le style de la page -->
    <link rel="stylesheet" href="../styles/output.css">
    <title>Connexion à la PACT</title>
    <!-- Inclusion de Font Awesome pour les icônes -->
    <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
</head>
<body class="h-screen bg-base100 p-4 overflow-hidden">
    <!-- Icône pour revenir à la page précédente -->
    <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>
    
    <div class="h-full flex flex-col items-center justify-center">
        <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
            <!-- Logo de l'application -->
            <img class="absolute -top-24" src="../public/images/logo.svg" alt="moine" width="108">

            <form class="bg-base200 w-full p-5 rounded-lg border-2 border-primary" action="login-member.php" method="post" enctype="multipart/form-data">
                <p class="pb-3">J'ai un compte Membre</p>
                
                <!-- Champ pour l'identifiant -->
                <label class="text-small" for="id">Identifiant</label>
                <input class="p-2 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="text" id="id" name="id" 
                       pattern="^(?:\w+|\w+[\.\-_]?\w+|0\d( \d{2}){4}|[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$" 
                       title="Saisir un identifiant (Pseudonyme, Adresse mail ou Téléphone)" maxlength="255" required>
                
                <!-- Champ pour le mot de passe -->
                <label class="text-small" for="mdp">Mot de passe</label>
                <div class="relative w-full">
                    <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp" 
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;])[A-Za-z\d!@#$%^&*(),.?&quot;:{}|&gt;&lt;]{8,}" 
                           title="Saisir un mot de passe" minlength="8" autocomplete="current-password" required>
                    <!-- Icône pour afficher/masquer le mot de passe -->
                    <i class="fa-regular fa-eye fa-lg absolute top-6 right-4 cursor-pointer" id="togglePassword"></i>
                </div>

                <!-- Messages d'erreurs -->
                <?php if (strlen($error)==0) { ?>
                    <span id="error-message" class="error text-rouge-logo text-small">
                        <?php echo htmlspecialchars($error);?>
                    </span>
                <?php } ?>

                <!-- Bouton de connexion -->
                <input type="submit" value="Me connecter" class="cursor-pointer w-full h-12 my-1.5 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">
                
                <!-- Liens pour mot de passe oublié et création de compte -->
                <div class="flex flex-nowrap h-12 space-x-1.5">
                    <a href="" class="text-small text-center w-full h-full p-1 text-wrap bg-transparent text-primary font-bold rounded-lg inline-flex items-center justify-center border border-primary hover:text-white hover:bg-orange-600 hover:border-orange-600 focus:scale-[0.97]"> 
                        Mot de passe oublié ?
                    </a>
                    <a href="create-member.php" class="text-small text-center w-full h-full p-1 text-wrap bg-transparent text-primary font-bold rounded-lg inline-flex items-center justify-center border border-primary hover:text-white hover:bg-orange-600 hover:border-orange-600 focus:scale-[0.97]"> 
                        Créer un compte
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<script>
    // Récupération de l'élément pour afficher/masquer le mot de passe
    const togglePassword = document.getElementById('togglePassword');
    const mdp = document.getElementById('mdp');

    // Événement pour afficher le mot de passe lorsque l'utilisateur clique sur l'icône
    togglePassword.addEventListener('mousedown', function () {
        mdp.type = 'text';
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash');
    });

    // Événement pour masquer le mot de passe lorsque l'utilisateur relâche le clic
    togglePassword.addEventListener('mouseup', function () {
        mdp.type = 'password';
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye');
    });
</script>

<?php } else {

session_start();
include('../dockerBDD/connexion/connect_params.php'); // Inclut le fichier de paramètres de connexion à la base de données

$error = ""; // Variable pour stocker les messages d'erreur

try {
    // Connexion à la base de données avec PDO
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

    // Vérifie si la requête est une soumission de formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id']; // Récupère l'id soumise
        $mdp = $_POST['mdp']; // Récupère le mot de passe soumis

        // Prépare une requête SQL pour trouver l'utilisateur par pseudo, email ou téléphone
        $stmt = $dbh->prepare("SELECT * FROM sae_db._membre WHERE pseudo = :id OR email = :id OR num_tel = :id");
        $stmt->bindParam(':id', $id); // Lie le paramètre à la valeur de l'email
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
            header('location: toutes-offres.html?token=' . $_SESSION['token']); // Redirige vers la page connectée
            exit();
        } else {
            $error = "Email ou mot de passe incorrect"; // Message d'erreur si les identifiants ne sont pas valides
        }
    }
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage(); // Affiche une erreur si la connexion échoue
    die(); // Arrête l'exécution du script
}

} ?>