<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparaison des clusters</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <style>
        #map { height: 600px; }
    </style>
</head>
<body>

    <button onclick="switchClusters('global')">Activer Cluster Global</button>
    <button onclick="switchClusters('ville')">Activer Cluster par Ville</button>
    
    <div id="map"></div>

    <script>
        // Initialiser la carte centrée sur la Bretagne
        var map = L.map('map').setView([48.1, -2.5], 7);

        // Ajouter une couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Villes en Bretagne avec leurs coordonnées
        var villes = {
            "Rennes": [48.1173, -1.6778],
            "Brest": [48.3904, -4.4861],
            "Quimper": [48.0000, -4.1000],
            "Vannes": [47.6582, -2.7608]
        };

        // Création des clusters
        var clusterGlobal = L.markerClusterGroup();
        var clustersParVille = {};

        Object.keys(villes).forEach(ville => {
            clustersParVille[ville] = L.markerClusterGroup();
        });

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

        // Ajouter des marqueurs
        Object.keys(villes).forEach(ville => {
            let points = generateRandomPoints(villes[ville][0], villes[ville][1], 100); // 100 points par ville
            points.forEach(coords => {
                let marker = L.marker(coords);

                // Ajouter au cluster global
                clusterGlobal.addLayer(marker);

                // Ajouter au cluster spécifique à la ville
                clustersParVille[ville].addLayer(marker);
            });
        });

        // Fonction pour changer de type de cluster
        function switchClusters(type) {
            // Supprimer tous les clusters de la carte
            map.eachLayer(layer => {
                if (layer instanceof L.MarkerClusterGroup) {
                    map.removeLayer(layer);
                }
            });

            if (type === 'global') {
                map.addLayer(clusterGlobal);
            } else if (type === 'ville') {
                Object.values(clustersParVille).forEach(cluster => {
                    map.addLayer(cluster);
                });
            }
        }

        // Activer le cluster global par défaut
        switchClusters('global');

    </script>

</body>
</html>
