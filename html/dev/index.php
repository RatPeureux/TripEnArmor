<?php
$offers = [
    ["name" => "Hôtel Rennes", "lat" => 48.1173, "lng" => -1.6778],
    ["name" => "Hôtel Brest", "lat" => 48.3904, "lng" => -4.4861],
    ["name" => "Hôtel Quimper", "lat" => 48.0000, "lng" => -4.1000],
    ["name" => "Hôtel Vannes", "lat" => 47.6582, "lng" => -2.7608],
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Carte des offres</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>

    <h2 class="text-center text-xl font-bold">Carte des Offres</h2>
    <div id="map"></div>

    <script>
        window.mapConfig = {
            center: [48.1, -2.5],
            zoom: 7,
            offers: <?php echo json_encode($offers); ?>
        };
    </script>
    <script src="map.js"></script>

</body>
</html>
