<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Controllers
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
$membreController = new MembreController();
$proPublicController = new ProPublicController();
$proPriveController = new ProPriveController();

$token = $_GET['token'];
$token_hash = hash('sha256', $token);

// Vérifier que le token est en ordre
$query = "SELECT * FROM sae_db._compte
  WHERE reset_token_hash = :token_hash";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':token_hash', $token_hash);
if ($stmt->execute()) {
    $result = $stmt->fetch();
    $role = 'visiteur';

    // Connaître le rôle de la personne (membre ou pro) 
    if ($membreController->getInfosMembre($result['id_compte'])) {
        $role = 'membre';
    } else if ($proPublicController->getInfosProPublic($result['id_compte']) || $proPriveController->getInfosProPrive($result['id_compte'])) {
        $role = 'professionnel';
    }

    // Mettre la bonne couleur clé en fonction du rôle (orange ou bleu)
    $couleur_role = ($role == 'professionnel') ? 'secondary' : 'primary';

    // // Si aucun compte trouvé
    if (!$result) {
        if ($role == 'professionnel') {
            header('Location: /pro/');
        } else {
            header('Location: /');
        }
        exit();
    }

    // Lien expiré
    if (strtotime($result['reset_token_expires_at']) <= time()) {
        die('Votre lien a expiré');
    }
}
?>

<?php if (empty($_POST)) { ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Changement de mot de passe - Professionnel - PACT</title>

        <!-- NOS FICHIERS -->
        <link rel="stylesheet" href="/styles/style.css">
        <script type="module" src="/scripts/main.js"></script>

    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">
        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/">
                    <img src="/public/icones/logo.svg" alt="Logo de TripEnArvor : Moine macareux" width="108">
                </a>

                <h2 class="mx-auto text-center text-2xl pt-4 my-4">Changement de mot de passe</h2>

                <form class="bg-white w-full p-5 border-2 border-<?php echo $couleur_role ?>" action="" method="POST">
                    <p class="text-sm">Définissez un nouveau mot de passe fiable, respectant les conditions
                        de sécurité minimum suivantes :</p>
                    <ul class="mb-3 text-sm">
                        <li>- 8 caratères</li>
                        <li>- 1 majuscule</li>
                        <li>- 1 caractère numérique</li>
                    </ul>

                    <!-- Champ pour le nouveau mot de passe -->
                    <div class="relative w-full">
                        <label class="text-sm" for="mdp">Nouveau mot de passe</label>
                        <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5" type="password" id="mdp" name="mdp"
                            pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$"
                            title="Saisir un mot de passe valide (au moins 8 caractères dont 1 majuscule et 1 chiffre)"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['mdp'] ?? '' ?>" required>
                        <!-- Oeil pour afficher le mot de passe -->
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>

                    <!-- Champ pour confirmer le nouveau mot de passe -->
                    <div class="relative w-full">
                        <label class="text-sm" for="confMdp">Confirmer le nouveau mot de passe</label>
                        <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5" type="password" id="confMdp" name="confMdp"
                            pattern="^(?=(.*[A-Z].*))(?=(.*\d.*))[\w\W]{8,}$"
                            title="Confirmer le mot de passe saisit ci-dessus"
                            value="<?php echo $_SESSION['data_en_cours_inscription']['confMdp'] ?? '' ?>" required>
                        <!-- Oeil pour afficher le mot de passe -->
                        <i
                            class="fa-regular fa-eye fa-lg absolute top-1/2 translate-y-2 right-4 cursor-pointer eye-toggle-password"></i>
                    </div>

                    <!-- Bouton de connexion -->
                    <input type="submit" value="Confirmer" onclick="return validatePassword()"
                        class="cursor-pointer w-full text-sm py-2 px-4 rounded-full h-12 my-1.5 bg-<?php echo $couleur_role ?> hover:bg-transparent hover:text-<?php echo $couleur_role ?> text-white border border-transparent hover:border-<?php echo $couleur_role ?> focus:scale-[0.97]">
                </form>
            </div>
        </div>

        <script>
            function validatePassword() {
                var password = document.getElementById("mdp").value;
                var confirmPassword = document.getElementById("confMdp").value;
                if (password !== confirmPassword) {
                    alert("Les mots de passe ne correspondent pas !");
                    return false;
                }
                return true;
            }
        </script>
    </body>

    </html>

<?php } else {
    // 2ème étape : changer le mdp dans la base de données
    try {
        // Pour éviter la casse du mot de passe si erreur
        $dbh->beginTransaction();

        $mdp_hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $query = "UPDATE sae_db._compte
                    SET mdp_hash = :mdp_hash,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                    WHERE reset_token_hash = :token_hash";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':token_hash', $token_hash);
        $stmt->bindParam(':mdp_hash', $mdp_hash);
        if ($stmt->execute()) {
            // Rediriger sur la bonne page de connexion
            if ($role == 'membre') {
                $_SESSION['message_pour_notification'] = 'Votre mot de passe a été réinitialisé';
                $dbh->commit();
                header('Location: /connexion');
            } else if ($role == 'professionnel') {
                $_SESSION['message_pour_notification'] = 'Votre mot de passe a été réinitialisé';
                $dbh->commit();
                header('Location: /pro/connexion');
            } else {
                $_SESSION['error'] = "Réinitialisation impossible : votre identifiant de compte ne corresond à aucun compte dans la base de données";
                $dbh->rollBack();
                header('Location: /connexion/reinitialisation');
            }
        } else {
            echo 'Erreur : impossible d\'exécuter la requête de changement de mot de passe';
            exit();
        }
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
        $dbh->rollBack();
        exit();
    }
}
?>