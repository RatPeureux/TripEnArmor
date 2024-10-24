<?php
include('../connect_params.php');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion à la base de données : ' . $e->getMessage()]);
    exit;
}

// Fonction pour calculer le prix minimum à partir des prix envoyés dans le formulaire
function calculerPrixMin($prices) {
    $minPrice = null;
    foreach ($prices as $price) {
        if (isset($price['value']) && (is_null($minPrice) || $price['value'] < $minPrice)) {
            $minPrice = $price['value'];
        }
    }
    return $minPrice;
}

// Fonction pour extraire des informations d'adresse
function extraireInfoAdresse($adresse) {
    $parts = explode(' ', $adresse, 2); // Sépare l'adresse en numéro et odonyme
    return [
        'numero' => isset($parts[0]) ? $parts[0] : '',
        'odonyme' => isset($parts[1]) ? $parts[1] : '',
    ];
}

// Partie pour traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $adresse = $_POST['user_input_autocomplete_address'] ?? '';
    $code = $_POST['postal_code'] ?? '';
    $ville = $_POST['locality'] ?? '';
    $age = $_POST['age'] ?? null; // age peut être vide
    $duree = !empty($_POST['duree']) ? $_POST['duree'] : '00:00:00';

    // Vérification de la durée
    if (is_numeric($duree)) {
        $hours = floor($duree / 60);
        $minutes = $duree % 60;
        $dureeFormatted = sprintf('%02d:%02d:00', $hours, $minutes); // Format HH:MM:SS
    } else {
        $dureeFormatted = '00:00:00'; // Valeur par défaut
    }

    // Récupérer d'autres valeurs
    $capacite = $_POST['place'] ?? '';
    $nb_attractions = isset($_POST['parc-numb']) && is_numeric($_POST['parc-numb']) ? (int)$_POST['parc-numb'] : 0;
    $gamme_prix = $_POST['gamme_prix'] ?? '';
    $description = $_POST['description'] ?? '';
    $resume = $_POST['resume'] ?? '';
    $prestations = $_POST['newPrestationName'] ?? '';
    $prices = $_POST['prices'] ?? []; // Récupérer les prix
    $titre = $_POST['titre'] ?? null;

    // Récupération des tags
    $tagsActivite = $_POST['tags']['activite'] ?? [];
    $tagsVisite = $_POST['tags']['visite'] ?? [];
    $tagsSpectacle = $_POST['tags']['spectacle'] ?? [];
    $tagsParcAttraction = $_POST['tags']['parc_attraction'] ?? [];
    $tagsRestauration = $_POST['tags']['restauration'] ?? [];

    // Validation du titre
    if ($titre === null || trim($titre) === '') {
        echo json_encode(['success' => false, 'error' => 'Le titre est requis.']);
        exit;
    }

    // Calculer le prix minimum parmi les tarifs
    $prixMin = calculerPrixMin($prices);

    // Insérer l'adresse dans la base de données
    $realAdresse = extraireInfoAdresse($adresse);
    try {
        $stmtAdresseOffre = $dbh->prepare("INSERT INTO sae_db._adresse (code_postal, ville, numero, odonyme, complement_adresse) VALUES (:postal_code, :locality, :numero, :odonyme, null)");
        $stmtAdresseOffre->bindParam(':postal_code', $code);
        $stmtAdresseOffre->bindParam(':locality', $ville);
        $stmtAdresseOffre->bindParam(':numero', $realAdresse['numero']);
        $stmtAdresseOffre->bindParam(':odonyme', $realAdresse['odonyme']);

        $stmtAdresseOffre->execute();
        $adresseId = $dbh->lastInsertId();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion de l\'adresse : ' . $e->getMessage()]);
        exit;
    }

    $dateCreation = date('Y-m-d H:i:s');

    // Insérer l'offre dans la table `Offre`
    try {
        $stmtOffre = $dbh->prepare("INSERT INTO sae_db._offre (est_en_ligne, description_offre, resume_offre, prix_mini, titre, date_creation, date_mise_a_jour, date_suppression, adresse_id) VALUES (true, :description, :resume, :prix, :titre, :date_creation, null, null, :adresse_id)");
        $stmtOffre->bindParam(':description', $description);
        $stmtOffre->bindParam(':resume', $resume);
        $stmtOffre->bindParam(':adresse_id', $adresseId);
        $stmtOffre->bindParam(':date_creation', $dateCreation);
        $stmtOffre->bindParam(':prix', $prixMin);
        $stmtOffre->bindParam(':titre', $titre);

        $stmtOffre->execute();
        $offreId = $dbh->lastInsertId(); // Récupérer l'ID de l'offre insérée
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion de l\'offre : ' . $e->getMessage()]);
        exit;
    }

    // Lier l'ID de l'offre et exécuter
    if ($stmtActivity) {
        $stmtActivity->bindParam(':offre_id', $offreId);
        try {
            $stmtActivity->execute();
            echo json_encode(['success' => true, 'offre_id' => $offreId]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion de l\'activité : ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Méthode de requête non autorisée.']);
}
?>
