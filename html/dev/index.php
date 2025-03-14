<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec Cluster Global</title>
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

    <h2>Carte de la Bretagne avec Cluster Global</h2>
    <div id="map"></div>

    <script>
        // Initialisation de la carte centrée sur la Bretagne
        var map = L.map('map').setView([48.1, -2.5], 7);

        // Ajouter la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Création du cluster global
        var clusterGlobal = L.markerClusterGroup();

        // Villes principales en Bretagne
        var villes = {
            "Rennes": [48.1173, -1.6778],
            "Brest": [48.3904, -4.4861],
            "Quimper": [48.0000, -4.1000],
            "Vannes": [47.6582, -2.7608]
        };

        // Générer des points aléatoires autour des villes
        function generateRandomPoints(baseLat, baseLng, count) {
            let points = [];
            for (let i = 0; i < count; i++) {
                let latOffset = (Math.random() - 0.5) * 0.1; // Variation légère en latitude
                let lngOffset = (Math.random() - 0.5) * 0.1; // Variation légère en longitude
                points.push([baseLat + latOffset, baseLng + lngOffset]);
            }
            return points;
        }

        // Ajouter des marqueurs au cluster global
        Object.keys(villes).forEach(ville => {
            let points = generateRandomPoints(villes[ville][0], villes[ville][1], 100); // 100 points par ville
            points.forEach(coords => {
                let marker = L.marker(coords);
                clusterGlobal.addLayer(marker);
            });
        });

        // Ajouter le cluster global à la carte
        map.addLayer(clusterGlobal);
    </script>

</body>
</html>
