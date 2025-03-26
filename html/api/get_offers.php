<?php
header("Content-Type: application/json");

// Connexion avec la BDD
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

/**
 * Fonction pour récupérer les coordonnées d'une adresse via Nominatim (OpenStreetMap)
 */
function geocodeAddress($address) {
    $query = urlencode($address);
    $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1";

    // Définir un User-Agent conforme aux règles d'OpenStreetMap
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: TripEnArvor/1.0 (pact.tripenarvor@gmail.com)\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    
    // Récupération des données
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        return null;
    }

    $data = json_decode($result, true);

    if (!empty($data)) {
        return [
            "lat" => $data[0]["lat"],
            "lng" => $data[0]["lon"]
        ];
    }

    return null;
}

// Récupérer toutes les offres en ligne
$stmt = $dbh->prepare("SELECT * FROM sae_db._offre WHERE est_en_ligne = true");
$stmt->execute();
$toutesLesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($toutesLesOffres as $index => $offre) {
    // Récupérer l'adresse de l'offre
    $stmt = $dbh->prepare("SELECT * FROM sae_db._adresse WHERE id_adresse = :id_adresse");
    $stmt->bindParam(':id_adresse', $offre['id_adresse']);
    $stmt->execute();
    $adresse = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si les coordonnées sont absentes ou à zéro
    if (empty($adresse['lat']) || empty($adresse['lng']) || $adresse['lat'] == "0" || $adresse['lng'] == "0") {
        $fullAddress = "{$adresse['numero']} {$adresse['odonyme']}, {$adresse['code_postal']} {$adresse['ville']}";
        $coords = geocodeAddress($fullAddress);

        if ($coords !== null) {
            $adresse['lat'] = $coords['lat'];
            $adresse['lng'] = $coords['lng'];

            // Mettre à jour la base de données avec les nouvelles coordonnées
            $updateStmt = $dbh->prepare("UPDATE sae_db._adresse SET lat = :lat, lng = :lng WHERE id_adresse = :id_adresse");
            $updateStmt->bindParam(':lat', $coords['lat']);
            $updateStmt->bindParam(':lng', $coords['lng']);
            $updateStmt->bindParam(':id_adresse', $adresse['id_adresse']);
            $updateStmt->execute();
        }
    }

    // Ajouter l'adresse mise à jour à l'offre
    $toutesLesOffres[$index]['adresse'] = $adresse;
}

// Retourner les données en JSON
echo json_encode($toutesLesOffres);
?>
