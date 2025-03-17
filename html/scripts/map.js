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

  // Charger les offres via AJAX
  fetch("/api/get_offers.php") // Assurez-vous que l'URL est correcte
      .then(response => response.json())
      .then(data => {
          console.log("Offres reçues :", data);

          data.forEach(offer => {
              if (offer.adresse && offer.adresse.lat && offer.adresse.lng) {
                  var marker = L.marker([offer.adresse.lat, offer.adresse.lng])
                      .bindPopup(`
                          <strong>${offer.titre}</strong><br>
                          ${offer.resume}<br>
                          <a href="/offre.php?id=${offer.id_offre}" target="_blank">Voir l'offre</a>
                      `);
                  clusterGroup.addLayer(marker);
              }
          });

          map.addLayer(clusterGroup);
      })
      .catch(error => console.error("Erreur lors du chargement des offres :", error));
});
