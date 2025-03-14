<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <!-- Make sure you put the js AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <title>Test Carte Interactive</title>
</head>

<body>
    <div id="map" style="width: 600px; height: 400px; position: relative;"></div>
    <script>
        var map = L.map('map').setView([48.202, -2.932], 8);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var restaurantIcon = L.icon({
            iconUrl: "/public/icones/restaurant.png",
        })

        var markerRestaurant = L.marker([48.734, -3.457], { icon: restaurantIcon, riseOnHover: true }).addTo(map).bindPopup("Restaurant");
        var markerActivite = L.marker([48.734, -3.458], riseOnHover = true).addTo(map).bindPopup("Activite");

        var clustersParVille = {};

        // Exemple de villes avec leurs coordonnées
        var villes = {
            "Rennes": [48.1173, -1.6778],
            "Brest": [48.3904, -4.4861],
            "Quimper": [48.0000, -4.1000],
            "Vannes": [47.6582, -2.7608]
        };

        // Initialiser un cluster par ville
        Object.keys(villes).forEach(ville => {
            clustersParVille[ville] = L.markerClusterGroup();
        });

        // Exemple de points avec attribution à la ville correspondante
        var locations = [
            { lat: 48.120, lng: -1.680, ville: "Rennes" },
            { lat: 48.118, lng: -1.675, ville: "Rennes" },
            { lat: 48.392, lng: -4.485, ville: "Brest" },
            { lat: 48.002, lng: -4.098, ville: "Quimper" },
            { lat: 47.660, lng: -2.758, ville: "Vannes" }
        ];

        // Ajouter les marqueurs aux clusters correspondants
        locations.forEach(function (loc) {
            var marker = L.marker([loc.lat, loc.lng]);
            clustersParVille[loc.ville].addLayer(marker);
        });

        // Ajouter les clusters à la carte
        Object.values(clustersParVille).forEach(cluster => {
            map.addLayer(cluster);
        });

    </script>
</body>

</html>