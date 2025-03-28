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
    if (!$result) {
        die('Le token de reset donné ne correspond à aucun dans la base de données');
    }
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

        <title>Réinitialisation de mot de passe</title>

        <!-- NOS FICHIERS -->
        <link rel="stylesheet" href="/styles/style.css">
    </head>

    <body>
        <h1>Réinitialisation de mot de passe</h1>

        <form method="post" action="" class='flex flex-col gap-2 items-start' onsubmit="return validatePassword()">

            <!-- Pour envoyer le token en le cachant -->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token) ?>">

            <label for="mdp">Nouveau mot de passe</label>
            <input type="password" id="mdp" name="mdp" required>

            <label for="confMdp">Confirmer le nouveau mot de passe</label>
            <input type="password" id="confMdp" name="confMdp" required>

            <input type="submit" value="Confirmer">
        </form>

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
    $token = $_POST['token'];
    $token_hash = hash('sha256', $token);

    // Vérifier que le token est valide
    $query = "SELECT * FROM sae_db._compte
              WHERE reset_token_hash = :token_hash";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':token_hash', $token_hash);
    if ($stmt->execute()) {
        $result = $stmt->fetch();
        if (!$result) {
            die('Le token de reset donné ne correspond à aucun dans la base de données');
        }
        if (strtotime($result['reset_token_expires_at'] <= time())) {
            die('Le token de reset donné a expiré');
        }
    }

    // Tout est en ordre, le changement de mot de passe peut opérer
    $mdp_hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    $query = "UPDATE sae_db._compte
                SET mdp_hash = :mdp_hash,
                reset_token_hash = NULL,
                reset_token_expires_at = NULL
                WHERE email = :email";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':mdp_hash', $mdp_hash);
    $stmt->bindParam(':email', $result['email']);

    // Rediriger sur la bonne page de connexion
    $_SESSION['message_pour_notification'] = 'Votre mot de passe a été réinitialisé';
    if ($membreController->getInfosMembre($result['id_compte'])) {
        header('Location: /connexion');
        exit();
    } else if ($proPublicController->getInfosProPublic($result['id_compte']) || $proPriveController->getInfosProPrive($result['id_compte'])) {
        header('Location: /pro/connexion');
        exit();
    } else {
        $_SESSION['error'] = "Réinitialisation impossible : votre identifiant de compte ne corresond à aucun compte dans la base de données";
        header('Location: /reset-mdp');
        exit();
    }
}
?>