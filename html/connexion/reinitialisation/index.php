<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Vider les messages d'erreur si c'est la première fois qu'on vient sur la page d'a2f
if (!isset($_SESSION['data_en_cours_reset'])) {
    unset($_SESSION['data_en_cours_connexion']);
    unset($_SESSION['data_en_cours_inscription']);
    unset($_SESSION['error']);
}

// Pour envoyer le mail
require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

if (empty($_POST)) { ?><!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- NOS FICHIERS -->
        <link rel="icon" href="/public/images/favicon.png">
        <link rel="stylesheet" href="/styles/style.css">
        <script type="module" src="/scripts/main.js"></script>

        <title>Réinitialisation du mot de passe - PACT</title>
    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">
        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/">
                    <img src="/public/icones/logo.svg" alt="Logo de TripEnArvor : Moine macareux" width="108">
                </a>

                <h2 class="mx-auto text-center text-2xl pt-4 my-4">Réinitialisation du mot de passe</h2>

                <form class="bg-white w-full p-5 border-2 border-primary" action="" method="POST">
                    <p class="text-sm">Nous allons vous envoyer un mail pour réinitialiser votre mot de passe</p>

                    <br>

                    <!-- Champ pour le mail -->
                    <label class="text-sm" for="mail">Email</label>
                    <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5" type="mail" id="email" name="email"
                        title="Saisir votre mot mail (doit contenir un '@')"
                        value="<?php echo $_SESSION['data_en_cours_reset']['email'] ?? '' ?>" required>

                    <!-- Message d'erreur -->
                    <span id="error-message" class="error text-rouge-logo text-sm">
                        <?php echo $_SESSION['error'] ?? '' ?>
                    </span>

                    <!-- Message de succès -->
                    <span class="error text-green-500 text-sm">
                        <?php echo $_GET['mail_sent'] ? 'Un lien de réinitialisation a été envoyé à votre boîte mail. Pensez à vérifier les spams.' : '' ?>
                    </span>

                    <!-- Bouton de connexion -->
                    <input type="submit" value="Envoyer"
                        class="cursor-pointer w-full text-sm py-2 px-4 rounded-full h-12 my-1.5 bg-primary hover:bg-orange-600 text-white inline-flex items-center justify-center border border-transparent focus:scale-[0.97">
                </form>
            </div>
        </div>
    </body>

    </html>

<?php } else {
    // 2ème étape : envoyer le mail de réinitialisation de mot de passe
    try {
        // Vérifie si la requête est une soumission de formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['data_en_cours_reset'] = $_POST;

            // Ce mail existe-t-il dans la base ?
            $stmt = $dbh->prepare('SELECT * from sae_db._compte WHERE email = :email');
            $stmt->bindParam(':email', $_POST['email']);
            if ($stmt->execute() && $stmt->rowCount() > 0) {

                $email = $_POST["email"];

                $token = bin2hex(random_bytes(16));
                $token_hash = hash("sha256", $token);
                $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

                $sql = "UPDATE sae_db._compte
                        SET reset_token_hash = :reset_token_hash,
                            reset_token_expires_at = :reset_token_expires_at
                        WHERE email = :email";

                $stmt = $dbh->prepare($sql);
                $stmt->bindParam('reset_token_hash', $token_hash);
                $stmt->bindParam('reset_token_expires_at', $expiry);
                $stmt->bindParam('email', $email);
                $stmt->execute();

                if ($stmt->rowCount()) {
                    // Préparer le service d'envoie de mail
                    $mail = require dirname($_SERVER['DOCUMENT_ROOT']) . "/php_files/mailer.php";

                    $mail->setFrom("noreply@example.com");
                    $mail->addAddress($email);
                    $mail->CharSet = 'UTF-8';
                    $mail->isHTML(true);
                    $mail->Subject = "Réinitialisation du mot de passe PACT";
                    $mail->Body = 'Cliquez <a href="localhost/connexion/reinitialisation/confirmation?token=' . $token . '">ici</a> pour réinitialiser votre mot de passe.';

                    try {
                        $mail->send();
                        unset($_SESSION['error']);
                        header('Location: /connexion/reinitialisation?mail_sent=true');
                        exit();
                    } catch (Exception $e) {
                        $_SESSION['error'] = 'Erreur lors de l\'envoi du mail. Veuillez réessayer plus tard.';
                        header('Location: /connexion/reinitialisation');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Cette adresse email ne correspond à aucun compte';
                    header('Location: /connexion/reinitialisation');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Cette adresse email ne correspond à aucun compte';
                header('Location: /connexion/reinitialisation');
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
