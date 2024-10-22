<?php
ob_start();
include('../connect_params.php');

$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO

// Alteration de la table pour s'assurer que numero_compte est un VARCHAR
try {
    $dbh->exec("ALTER TABLE sae_db.Rib ALTER COLUMN numero_compte TYPE VARCHAR(11)");
} catch (PDOException $e) {
    // Ignorer l'erreur si la colonne est déjà au bon type
    if ($e->getCode() !== '42P07') {
        // 42P07 est le code d'erreur pour une table ou colonne déjà existante
        throw $e;
    }
}

$message = ''; // Initialiser le message

function extraireRibDepuisIban($iban) {
    // Supprimer les espaces et vérifier que l'IBAN est bien de 27 caractères
    $iban = str_replace(' ', '', $iban);

    if (strlen($iban) != 27) {
        throw new Exception("L'IBAN doit comporter 27 caractères.");
    }

    $code_banque = substr($iban, 5, 5);
    $code_guichet = substr($iban, 10, 5);
    $numero_compte = substr($iban, 15, 11);
    $cle_rib = substr($iban, 26, 2);

    return [
        'code_banque' => $code_banque,
        'code_guichet' => $code_guichet,
        'numero_compte' => $numero_compte,
        'cle_rib' => $cle_rib,
    ];
}

// Partie pour traiter la soumission du second formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_tel'])) {
    // Assurer que tous les champs obligatoires sont remplis
    $adresse = $_POST['adresse'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];
    $mdp = $_POST['mdp'];
    $nom = $_POST['nom'];
    $mail = $_POST['mail'];
    $pseudo = $_POST['pseudo'];
    $tel = $_POST['num_tel'];
    $compte_id = $_POST['compte_id'];
    $iban = $_POST['iban']; // Assurez-vous que l'IBAN est récupéré du formulaire

    // Hachage du mot de passe
    if (!empty($mdp)) {
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

        // Insérer dans la base de données pour l'adresse
        $stmtAdresse = $dbh->prepare("INSERT INTO sae_db.Adresse (adresse_postale, code_postal, ville) VALUES (:adresse, :code, :ville)");
        $stmtAdresse->bindParam(':ville', $ville);
        $stmtAdresse->bindParam(':adresse', $adresse);
        $stmtAdresse->bindParam(':code', $code);
        
        if ($stmtAdresse->execute()) {
            $adresseId = $dbh->lastInsertId();

            // Préparer l'insertion dans la table Professionnel
            $stmtProfessionnel = $dbh->prepare("INSERT INTO sae_db.Professionnel (email, mdp_hash, num_tel, adresse_id, nom_orga) VALUES (:mail, :mdp, :num_tel, :adresse_id, :nom)");
            $stmtProfessionnel->bindParam(':mail', $mail);
            $stmtProfessionnel->bindParam(':mdp', $mdp_hache);
            $stmtProfessionnel->bindParam(':nom', $nom);
            $stmtProfessionnel->bindParam(':num_tel', $tel);
            $stmtProfessionnel->bindParam(':adresse_id', $adresseId);

            // Exécuter la requête pour le professionnel
            if ($stmtProfessionnel->execute()) {
                // Extraire les valeurs du RIB à partir de l'IBAN
                try {
                    $rib = extraireRibDepuisIban($iban);
                    $stmtRib = $dbh->prepare("INSERT INTO sae_db.Rib (code_banque, code_guichet, numero_compte, cle_rib, compte_id) VALUES (:code_banque, :code_guichet, :numero_compte, :cle_rib, :compte_id)");
                    $stmtRib->bindParam(':code_banque', $rib['code_banque']);
                    $stmtRib->bindParam(':code_guichet', $rib['code_guichet']);
                    $stmtRib->bindParam(':numero_compte', $rib['numero_compte']);
                    $stmtRib->bindParam(':cle_rib', $rib['cle_rib']);
                    $stmtRib->bindParam(':compte_id', $compte_id); // Assurez-vous que compte_id est défini

                    if ($stmtRib->execute()) {
                        $message = "Votre compte a bien été créé. Vous allez maintenant être redirigé vers la page de connexion.";
                    } else {
                        $message = "Erreur lors de l'insertion dans la table RIB : " . implode(", ", $stmtRib->errorInfo());
                    }
                } catch (Exception $e) {
                    $message = "Erreur lors de l'extraction des données RIB : " . $e->getMessage();
                }
            } else {
                $message = "Erreur lors de la création du compte professionnel : " . implode(", ", $stmtProfessionnel->errorInfo());
            }
        } else {
            $message = "Erreur lors de l'insertion dans la table Adresse : " . implode(", ", $stmtAdresse->errorInfo());
        }
    } else {
        $message = "Mot de passe manquant.";
    }
}

ob_end_flush();
?>

<!-- Affichage du message dans le HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <script>
        // Fonction de redirection après un délai
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = "../../../pages/login-pro.html";
            }, 5000); // 5000 ms = 5 secondes
        }
    </script>
</head>
<body>
    <h1>Création de Compte</h1>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <script>redirectToLogin();</script>
    <?php endif; ?>

    <!-- Formulaire de création de compte ici -->
</body>
</html>
