<?php
    session_start(); // Démarre la session pour accéder aux variables de session
    ob_start(); // Active la mise en mémoire tampon de sortie

include("/php/connect_params.php"); // Inclut le fichier de configuration pour la connexion à la base de données

try {
    // Établit la connexion à la base de données avec PDO
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configure PDO pour lancer des exceptions en cas d'erreur

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si un ID d'offre a été fourni
    if (isset($_POST['idoffre'])) {
        $idoffre = $_POST['idoffre'];
        
        // Debug : affichez la valeur reçue
        error_log("Valeur d'idoffre : " . $idoffre); // Cela envoie le message à votre log d'erreurs PHP

        // Vérifiez si $idoffre est un entier
        if (!is_numeric($idoffre)) {
            $_SESSION['message'] = "ID d'offre invalide : " . htmlspecialchars($idoffre);
            header("Location: /pages/accueil-pro.php"); // Redirection après message d'erreur
            exit();
        }
            // Récupérer l'état actuel de l'offre
            $stmt = $dbh->prepare("SELECT est_en_ligne FROM sae_db.Offre WHERE offre_id = :idoffre");
            $stmt->execute(['idoffre' => $idoffre]);
            $offre = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($offre === false) {
                $_SESSION['message'] = "Offre non trouvée.";
                sleep(5);
                header("Location: /pages/accueil-pro.php"); // Redirection après message d'erreur
                exit();
            }

            // Vérifiez l'état actuel de l'offre
            $est_en_ligne = $offre['est_en_ligne']; // Récupère l'état actuel de l'offre

            if ($est_en_ligne) {
                // Met à jour l'état de l'offre pour la mettre hors ligne
                $stmt = $dbh->prepare("UPDATE sae_db.Offre SET est_en_ligne = false WHERE offre_id = :idoffre");
                $stmt->execute(['idoffre' => $idoffre]);
                $_SESSION['message'] = "L'offre a été mise hors ligne avec succès.";
                header("location: /pages/accueil-pro.php");
            } else {
                // Met à jour l'état de l'offre pour la mettre en ligne
                $stmt = $dbh->prepare("UPDATE sae_db.Offre SET est_en_ligne = true WHERE offre_id = :idoffre");
                $stmt->execute(['idoffre' => $idoffre]);
                $_SESSION['message'] = "L'offre a été mise en ligne avec succès.";
                header("location: /pages/accueil-pro.php");
            }
            exit(); // Termine le script pour s'assurer que la redirection fonctionne
        }
    }

} catch (PDOException $e) {
    // Affiche une erreur en cas de problème de connexion à la base de données
    echo "Erreur de connexion : " . $e->getMessage();
    exit(); // Termine le script
}
?>
