<?php
try {
    // Connexion à la base de données
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

    // Récupération des offres en ligne
    $stmt = $dbh->prepare("
        SELECT o.*, a.code_postal, a.ville, a.numero, a.odonyme, a.complement 
        FROM sae_db._offre o 
        JOIN sae_db._adresse a ON o.id_adresse = a.id_adresse
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