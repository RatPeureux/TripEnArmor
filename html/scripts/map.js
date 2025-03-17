document.addEventListener("DOMContentLoaded", function () {
  const mapData = window.mapConfig;

  // Initialiser la carte centrée sur l’offre ou par défaut
  var map = L.map("map").setView(mapData.center, mapData.zoom);

  // Ajouter la couche OpenStreetMap
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  var clusterGroup = L.markerClusterGroup();

  // Si une offre spécifique est sélectionnée, on l'affiche en premier
  if (mapData.selectedOffer && mapData.selectedOffer.lat && mapData.selectedOffer.lng) {
      var selectedMarker = L.marker([mapData.selectedOffer.lat, mapData.selectedOffer.lng])
          .bindPopup(`<strong>${mapData.selectedOffer.name}</strong>`);
      clusterGroup.addLayer(selectedMarker);
      map.addLayer(clusterGroup);

      // Zoomer directement sur l'offre spécifique
      map.setView([mapData.selectedOffer.lat, mapData.selectedOffer.lng], 14);
  }

  // Charger les autres offres dynamiquement via AJAX
  fetch("/api/get_offers.php")
      .then(response => response.json())
      .then(data => {
          data.forEach(offer => {
              if (offer.adresse && offer.adresse.lat && offer.adresse.lng) {
                  var marker = L.marker([offer.adresse.lat, offer.adresse.lng])
                      .bindPopup(`
                          <strong>${offer.titre}</strong><br>
                          ${offer.resume}<br>
                          <a href="/offre/?détails=${offer.id_offre}" target="_blank">Voir l'offre</a>
                      `);
                  clusterGroup.addLayer(marker);
              }
          });

          map.addLayer(clusterGroup);
      })
      .catch(error => console.error("Erreur lors du chargement des offres :", error));
});
