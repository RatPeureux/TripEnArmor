const provider = new GeoSearch.OpenStreetMapProvider();
const map = L.map('map').setView([48.176197, -2.753931], 8);

L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const search = new GeoSearch.GeoSearchControl({
  notFoundMessage: 'Désolé, nous ne trouvons pas cette adresse.',
  provider: new GeoSearch.OpenStreetMapProvider(),
  style: 'bar',
});

map.addControl(search);

// Remplir les champs lorsqu'un endroit ests sélectionné
let currentMarker = null;

map.on('geosearch/showlocation', function (event) {
  const selectedResult = event.location;
  const address = selectedResult.label;
  const latlng = L.latLng(selectedResult.y, selectedResult.x);

  // Créer un marqueur et l'ajouter à la carte
  if (currentMarker) {
    map.removeLayer(currentMarker); // Supprimer le précédent marqueur
  }

  currentMarker = L.marker(latlng).addTo(map);
  currentMarker.bindPopup("Cliquez pour confirmer cette adresse").openPopup();

  // Ne pas remplir automatiquement les champs tout de suite
  document.getElementById("user_input_autocomplete_address").dataset.address = address;

  // Ajouter l'événement pour valider l'adresse au clic sur le marqueur
  currentMarker.on('click', function () {
    const lat = latlng.lat;
    const lng = latlng.lng;

    // Ajouter les coordonnées dans les champs cachés du formulaire
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;

    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1&accept-language=fr`;

    fetch(url)
      .then(response => response.json())
      .then(data => {
        if (data && data.address) {
          const address = data.address;
          // Récupérer adresse, ville et code postal grâce aux coordonnées et à l'api de nominatim
          const numero = address.house_number ? address.house_number : ''; // Numéro de rue
          const street = address.road || address.house_number || 'Adresse non trouvée';
          const city = address.city || address.town || address.village || 'Ville non trouvée';
          const postalCode = address.postcode || 'Code postal non trouvé';

          // Remplir les champs de saisie
          document.getElementById("user_input_autocomplete_address").value = `${numero} ${street}`;
          document.getElementById("postal_code").value = postalCode;
          document.getElementById("locality").value = city;

          // Déclencher manuellement les événements 'input' ou 'change'
          document.getElementById("user_input_autocomplete_address").dispatchEvent(new Event('input'));
          document.getElementById("postal_code").dispatchEvent(new Event('input'));
          document.getElementById("locality").dispatchEvent(new Event('input'));
        } else {
          console.error('Impossible de récupérer les informations d\'adresse.');
        }
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des différents éléments de l\'adresse : ', error);
      });
    // Cacher la map quand le point est cliqué
    document.getElementById('map-container').classList.add('hidden');
  });
});

function showMap() {
  const mapElement = document.getElementById('map-container');
  mapElement.classList.remove('hidden');
  if (!mapElement) {
    console.log('Map container not found!');
    return;
  }

  // Fixer l'affichage de la map
  setTimeout(function () {
    map.invalidateSize();
  }, 200);
}
window.showMap = showMap;

function reorderFocusOnSelectAddress(input, refocus) {
  input.addEventListener("focus", function () {
    if (input.value == "") {
      input.blur();
      refocus.click();
    }
  });
}
reorderFocusOnSelectAddress(document.getElementById('user_input_autocomplete_address'), document.getElementById('select-on-map'));
reorderFocusOnSelectAddress(document.getElementById('locality'), document.getElementById('select-on-map'));
reorderFocusOnSelectAddress(document.getElementById('postal_code'), document.getElementById('select-on-map'));
