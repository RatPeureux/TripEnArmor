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

        <title>Reset le mot de passe - PACT</title>
    </head>

    <body class="h-screen bg-white p-4 overflow-hidden">
        <div class="h-full flex flex-col items-center justify-center">
            <div class="relative w-full max-w-96 h-fit flex flex-col items-center justify-center sm:w-96 m-auto">
                <!-- Logo de l'application -->
                <a href="/">
                    <img src="/public/icones/logo.svg" alt="Logo de TripEnArvor : Moine macareux" width="108">
                </a>

                <h2 class="mx-auto text-center text-2xl pt-4 my-4">Réinitialisation</h2>

                <form class="bg-white w-full p-5 border-2 border-secondary" action="" method="POST">
                    <p class="text-sm">Nous allons vous envoyer un mail pour réinitialiser votre mot de passe</p>

                    <br>

                    <!-- Champ pour le mail -->
                    <label class="text-sm" for="mail">Email</label>
                    <input class="p-2 pr-12 bg-base100 w-full h-12 mb-1.5" type="mail" id="email" name="email"
                        title="Saisir votre mot mail (doit contenir un '@')"
                        value="<?php echo $_SESSION['data_en_cours_reset']['email'] ?? '' ?>" required>

                    <span id="error-message" class="error text-rouge-logo text-sm">
                        <?php echo $_SESSION['error'] ?? '' ?>
                    </span>

                    <!-- Bouton de connexion -->
                    <input type="submit" value="Envoyer"
                        class="cursor-pointer w-full text-sm py-2 px-4 rounded-full h-12 my-1.5 bg-secondary hover:bg-black text-white inline-flex items-center justify-center border border-transparent focus:scale-[0.97">
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

            // Vérifier si le mail existe pour un compte
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

                $response = ['status' => 'error', 'message' => ''];

                if ($stmt->rowCount()) {
                    // Préparer le service d'envoie de mail
                    $mail = require dirname($_SERVER['DOCUMENT_ROOT']) . "/mailer.php";

                    $mail->setFrom("noreply@example.com");
                    $mail->addAddress($email);
                    $mail->Subject = "Réinitialisation mdp PACT";
                    $mail->Body = "Cliquez <a href='http://example.com/reset-password.php?token=" . $token . "'>ici</a> pour réinitialiser votre mot de passe";

                    try {
                        $mail->send();
                        $response['status'] = 'success';
                        $response['message'] = 'Mail envoyé. Pensez à consulter vos spams.';
                    } catch (Exception $e) {
                        $response['message'] = "Impossible d'envoyer le mail : {$mail->ErrorInfo}";
                    }
                } else {
                    $response['message'] = 'Le compte avec cet email n\'existe pas.';
                }

                if ($response['status'] === 'success') {
                    echo 'Succès : ' . $response['message'];
                } else {
                    print_r($response);
                    echo 'Erreur :' . $response['message'];
                }
            } else {
                $_SESSION['error'] = 'Cette adresse email ne correspond à aucun compte';
                header('Location: /reset_mdp');
            }
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
