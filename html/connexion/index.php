<?php
session_start(); // Démarre la session au début du script
// Vider les messages d'erreur si c'est la première fois qu'on vient sur la page de connexion
if (!isset($_SESSION['data_en_cours_connexion'])) {
    unset($_SESSION['error']);
}
if (empty($_POST)) { ?>

<!-- 1ère étape : remplir les informations de connexion -->
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Lien vers le favicon de l'application -->
        <link rel="icon" type="image" href="/public/images/favicon.png">
        <!-- Lien vers le fichier CSS pour le style de la page -->
        <script rel="stylesheet" href="/styles/input.css">
    <script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    content: [
        "./html/**/*",
    ],
    theme: {
        extend: {
            fontFamily: {
                'cormorant': ['Cormorant-Bold'],
                'sans': ['Poppins'],
            },
            fontSize: {
                'small': ['14px'],
                'h1': ['32px'],
                'h2': ['24px'],
                'h3': ['20px'],
                'h4': ['18px'],
                'PACT': ['35px', {
                    letterSpacing: '0.2em',
                }],
            },
            colors: {
                'rouge-logo': '#EA4335',
                'primary': '#F2771B',
                'secondary': '#0a0035',
                'base100': '#F1F3F4',
                'base200': '#E0E0E0',
                'base300': '#CCCCCC',
                'neutre': '#000',
                'gris': '#828282',
                'bgBlur': "#F1F3F4",
                'veryGris': "#BFBFBF",
            },
            spacing: {
                '1/6': '16%',
            },
            animation: {
                'expand-width': 'expandWidth 1s ease-out forwards',
            },
            keyframes: {
                expandWidth: {
                    '0%': { width: '100%' },
                    '100%': { width: '0%' },
                },
            },
            boxShadow: {
                'custom': '0 0 12px 12px rgba(210, 210, 210, 0.5)',
            }
        },
    },
    plugins: [],
}
</script>
        <title>Connexion au compte</title>
        <!-- Inclusion de Font Awesome pour les icônes -->
        <script src="https://kit.fontawesome.com/d815dd872f.js" crossorigin="anonymous"></script>
    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">
        <!-- Icône pour revenir à la page précédente -->
        <i onclick="history.back()" class="fa-solid fa-arrow-left fa-2xl cursor-pointer"></i>

        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/" class="w-full">
                    <img class="relative mx-auto -top-8" src="/public/images/logo.svg" alt="moine" width="108">
                </a>

                <form class="bg-base100 w-full p-5 rounded-lg border-2 border-primary" action="/connexion" method="POST">
                    <p class="pb-3">J'ai un compte Membre</p>

                    <!-- Champ pour l'identifiant -->
                    <label class="text-small" for="id">Identifiant (pseudo, téléphone, mail)</label>
                    <input class="p-2 bg-white w-full h-12 mb-1.5 rounded-lg" type="text" id="id" name="id"
                        title="pseudo / mail / téléphone"
                        maxlength="255" value="<?php echo $_SESSION['data_en_cours_connexion']['id']; ?>" required>

                    <!-- Champ pour le mot de passe -->
                    <div class="relative w-full">
                        <label class="text-small" for="mdp">Mot de passe</label>
                        <input class="p-2 pr-12 bg-white w-full h-12 mb-1.5 rounded-lg" type="password" id="mdp" name="mdp"
                            pattern=".*[A-Z].*.*\d.*|.*\d.*.*[A-Z].*" title="
                            • 8 caractères au moins
                            • 1 majuscule
                            • 1 chiffre" minlength="8" autocomplete="new-password" value="<?php echo $_SESSION['data_en_cours_connexion']['mdp']; ?>" required>
                        <!-- Icône pour afficher/masquer le mot de passe -->
                        <i class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer"
                            id="togglePassword"></i>
                    </div>

                    <span id="error-message" class="error text-rouge-logo text-small">
                        <?php echo $_SESSION['error']; ?>
                    </span>

                    <!-- Bouton de connexion -->
                    <input type="submit" value="Me connecter"
                        class="cursor-pointer mb-8 w-full h-12 my-1.5 bg-primary text-white font-bold rounded-lg inline-flex items-center justify-center border border-transparent focus:scale-[0.97] hover:bg-orange-600 hover:border-orange-600 hover:text-white">

                    <!-- Liens pour mot de passe oublié et création de compte -->
                    <div class="flex items-center flex-nowrap h-12 space-x-1.5">
                        <a href="#"
                            class="text-small text-center w-full text-wrap bg-transparent text-primary underline font-bold focus:scale-[0.97]">
                            Mot de passe oublié ?
                        </a>
                        <a href="/inscription"
                            class="text-small text-center w-full h-full p-1 text-wrap bg-transparent text-primary font-bold rounded-lg inline-flex items-center justify-center border border-primary hover:text-white hover:bg-orange-600 hover:border-orange-600 focus:scale-[0.97]">
                            Créer un compte
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <script>
        // Récupération de l'élément pour afficher/masquer le mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const mdp = document.getElementById('mdp');

        // Événement pour afficher le mot de passe lorsque l'utilisateur clique sur l'icône
        if (togglePassword) {
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
        }
    </script>

    </html>

<!-- 2ème étape, essayer de se connecter à la base, et inscrire une erreur sinon -->
<?php } else {
    try {
        // Connexion avec la bdd
        include dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

        // Vérifie si la requête est une soumission de formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //  Pour garder les informations dans le formulaire si erreur
            $_SESSION['data_en_cours_connexion'] = $_POST;
            $id = $_POST['id']; // Récupère l'id soumise
            $mdp = $_POST['mdp']; // Récupère le mot de passe soumis

            // Prépare une requête SQL pour trouver l'utilisateur par nom, email ou numéro de téléphone
            $stmt = $dbh->prepare("SELECT * FROM sae_db._membre WHERE pseudo = :id OR email = :id OR num_tel = :id");
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
                    // Connecte le membre et retirer toute éventuelle information de connexino à un compte pro
                    $_SESSION['id_membre'] = $user['id_compte'];
                    unset($_SESSION['id_pro']);
                    header('location: /'); // Redirige vers la page connectée
                    exit();
                } else {
                    $_SESSION['error'] = "Mot de passe incorrect"; // Stocke le message d'erreur dans la session
                    header('location: /connexion'); // Retourne à la page de connexion
                    exit();
                }
            } else {
                $_SESSION['error'] = "Nous ne trouvons pas de compte avec cet identifiant"; // Stocke le message d'erreur dans la session
                header('location: /connexion'); // Retourne à la page de connexion
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Erreur !: " . $e->getMessage(); // Affiche une erreur si la connexion échoue
        die(); // Arrête l'exécution du script
    }
} ?>