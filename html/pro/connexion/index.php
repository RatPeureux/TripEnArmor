<?php
session_start(); // Démarre la session au début du script
global $error; // Variable pour stocker les messages d'erreur
global $id; // Variable pour stocker les messages d'erreur

if (!isset($_POST['id'])) {
    // Vérifie si un message d'erreur est stocké dans la session
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error']; // Récupère le message d'erreur de la session
        $id = $_SESSION['id']; // Récupère l'id utilisé avant l'erreur
        unset($_SESSION['error']); // Supprime le message d'erreur de la session après l'affichage
        unset($_SESSION['id']); // Supprime l'id' après l'affichage
    } ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Lien vers le favicon de l'application -->
        <link rel="icon" type="image" href="/public/images/favicon.png">
        <!-- Lien vers le fichier CSS pour le style de la page -->
        <link rel="stylesheet" href="/styles/output.css">
        <title>Connexion à la PACT</title>
        <!-- Inclusion de Font Awesome pour les icônes -->
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">
        <!-- Icône pour revenir à la page précédente -->
        <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/pro" class="w-full">
                    <img class="relative mx-auto -top-8" src="../public/images/logo.svg" alt="moine" width="108">
                </a>

                <!-- Notification du compte bien créé -->
                <?php
                if (isset($_GET['created'])) { ?>
                    <div class="relative">
                        <p class="bg-secondary/75 text-white p-2 rounded-lg mb-2 border border-primary">Compte créé avec succès ✓</p>
                        <div class="absolute top-0 w-full h-full z-10 bg-white animate-expand-width"></div>
                    </div>
                <?php } ?>

                <form class="bg-base100 w-full p-5 rounded-lg border-2 border-secondary" action="/pro/connexion"
                    method="post" enctype="multipart/form-data">
                    <p class="pb-3">J'ai un compte Professionnel</p>

                    <!-- Champ pour l'identifiant -->
                    <label class="text-small" for="id">Identifiant</label>
                    <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="id" name="id"
                        title="Saisir un identifiant (Dénomination / Nom de l'organisation, Adresse mail ou Téléphone)"
                        value="<?php echo $id; ?>" maxlength="255" required>

                    <!-- Champ pour le mot de passe -->
                    <div class="relative w-full">
                        <label class="text-small" for="mdp">Mot de passe</label>
                        <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp"
                            pattern=".*[A-Z].*.*\d.*|.*\d.*.*[A-Z].*" title="
                            • 8 caractères au moins
                            • 1 majuscule
                            • 1 chiffre" minlength="8" autocomplete="new-password" required>
                        <!-- Icône pour afficher/masquer le mot de passe -->
                        <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                            id="togglePassword"></i>
                    </div>

                    <?php if ($error !== "") { ?>
                        <!-- Messages d'erreurs -->
                        <span id="error-message" class="error text-rouge-logo text-small">
                            <?php echo $error; ?>
                        </span>
                    <?php } ?>

                    <!-- Bouton de connexion -->
                    <input type="submit" value="Me connecter"
                        class="cursor-pointer w-full h-12 my-1.5 bg-secondary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-secondary/90 hover:border-secondary/90 hover:text-white">

                    <!-- Liens pour mot de passe oublié et création de compte -->
                    <div class="flex flex-nowrap h-12 space-x-1.5">
                        <a href="#"
                            class="text-small text-center w-full h-full p-1 text-wrap bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-secondary/90 hover:border-secondary/90 focus:scale-[0.97]">
                            Mot de passe oublié ?
                        </a>
                        <a href="/pro/inscription"
                            class="text-small text-center w-full h-full p-1 text-wrap bg-transparent text-secondary font-bold rounded-lg inline-flex items-center justify-center border border-secondary hover:text-white hover:bg-secondary/90 hover:border-secondary/90 focus:scale-[0.97]">
                            Créer un compte
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>

<?php } else {

    $error = ""; // Variable pour stocker les messages d'erreur
    try {
        // Connexion avec la bdd
        include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

        // Vérifie si la requête est une soumission de formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; // Récupère l'id soumise
            $mdp = $_POST['mdp']; // Récupère le mot de passe soumis

            // Prépare une requête SQL pour trouver l'utilisateur par nom, email ou numéro de téléphone
            $stmt = $dbh->prepare("SELECT * FROM sae_db._professionnel WHERE nom_pro = :id OR email = :id OR num_tel = :id");
            $stmt->bindParam(':id', $id); // Lie le paramètre à la valeur de l'id
            $stmt->execute(); // Exécute la requête

            // Vérifie s'il y a une erreur SQL
            if ($stmt->errorInfo()[0] !== '00000') {
                error_log("SQL Error: " . print_r($stmt->errorInfo(), true)); // Log l'erreur
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données de l'utilisateur
            error_log(print_r($user, true)); // Log les données de l'utilisateur pour débogage

            // Vérifie si l'utilisateur existe et si le mot de passe est correct
            if ($user) {
                if (password_verify($mdp, $user['mdp_hash'])) {
                    // Stocke les informations de l'utilisateur dans la session
                    $_SESSION['id_pro'] = $user['id_compte'];
                    header('location: /'); // Redirige vers la page connectée
                    exit();
                } else {
                    $_SESSION['error'] = "Mot de passe incorrect"; // Stocke le message d'erreur dans la session
                    header('location: /pro/connexion'); // Retourne à la page de connexion
                    exit();
                }
            } else {
                $_SESSION['error'] = "Nous ne trouvons pas de compte avec cet identifiant"; // Stocke le message d'erreur dans la session
                header('location: /pro/connexion'); // Retourne à la page de connexion
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage(); // Affiche une erreur si la connexion échoue
        die(); // Arrête l'exécution du script
    }

} ?>

<script>
    // Récupération de l'élément pour afficher/masquer le mot de passe
    const togglePassword = document.getElementById('togglePassword');
    const mdp = document.getElementById('mdp');

    // Événement pour afficher le mot de passe lorsque l'utilisateur clique sur l'icône
    togglePassword.addEventListener('mousedown', function () {
        mdp.type = 'text'; // Change le type d'input pour afficher le mot de passe
        this.classList.remove('fa-eye'); // Change l'icône pour indiquer que le mot de passe est visible
        this.classList.add('fa-eye-slash');
    });

    // Événement pour masquer le mot de passe lorsque l'utilisateur relâche le clic
    togglePassword.addEventListener('mouseup', function () {
        mdp.type = 'password'; // Change le type d'input pour masquer le mot de passe
        this.classList.remove('fa-eye-slash'); // Réinitialise l'icône
        this.classList.add('fa-eye');
    });
</script>