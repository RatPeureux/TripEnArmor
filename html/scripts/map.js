document.addEventListener("DOMContentLoaded", function () {
    // Récupérer les données JSON injectées dans le script HTML
    const mapData = window.mapConfig;

    // Initialiser la carte avec le centre et le zoom définis
    var map = L.map("map").setView(mapData.center, mapData.zoom);

    // Ajouter la couche OpenStreetMap
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // Ajouter un cluster group
    var clusterGroup = L.markerClusterGroup();

    // Ajouter les marqueurs des offres
    mapData.offers.forEach((offer) => {
        var marker = L.marker([offer.lat, offer.lng]).bindPopup(offer.name);
        clusterGroup.addLayer(marker);
    });

    // Ajouter le cluster à la carte
    map.addLayer(clusterGroup);
});
