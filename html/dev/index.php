<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

	var markerRestaurant = L.marker([48.734, -3.457], {icon: restaurantIcon, riseOnHover: true}).addTo(map).bindPopup("Restaurant");
	var markerActivite = L.marker([48.734, -3.458], riseOnHover=true).addTo(map).bindPopup("Activite");
    </script>
</body>

</html>
