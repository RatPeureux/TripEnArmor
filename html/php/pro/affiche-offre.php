<?php
// Inclure la connexion à la base de données
include('/php/connect_params.php'); // Assurez-vous que ce fichier contient la connexion à votre base de données

try {
    // Connexion à la base de données
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des offres en ligne
    $stmt = $dbh->prepare("
        SELECT o.*, a.code_postal, a.ville, a.numero, a.odonyme, a.complement_adresse 
        FROM sae_db._offre o 
        JOIN sae_db._adresse a ON o.adresse_id = a.adresse_id
    ");
    $stmt->execute();
    $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug: voir le contenu des offres récupérées
    if (empty($offres)) {
        error_log('Aucune offre trouvée dans la base de données.', 0);
    } else {
        error_log('Nombre d\'offres récupérées: ' . count($offres), 0);
    }

    // Retourner les résultats sous forme de JSON
    header('Content-Type: application/json'); // Spécifier que la réponse est en JSON
    echo json_encode($offres);
} catch (PDOException $e) {
    // En cas d'erreur, loguer le message d'erreur et le renvoyer en JSON
    error_log("Erreur de connexion à la base de données : " . $e->getMessage(), 0);
    header('Content-Type: application/json'); // Assurez-vous que la réponse est en JSON même en cas d'erreur
    echo json_encode(['error' => 'Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage())]);
}

error_log('Données récupérées : ' . print_r($offres, true), 0);

?>
