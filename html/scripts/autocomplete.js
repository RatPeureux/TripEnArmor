let autocomplete;

function initializeAutocomplete(id) {
    const element = document.getElementById(id);
    const options = {
        componentRestrictions: { country: 'FR' },
    };

    if (element) {
        autocomplete = new google.maps.places.Autocomplete(element, options);
        autocomplete.addListener('place_changed', fillInAddress); // Écoute le changement de place
    }
}

function fillInAddress() {
    const place = autocomplete.getPlace();
    let address1 = "";
    let postalCode = "";
    let locality = "";

    for (const component of place.address_components) {
        // Obtenir le type de champ pour chaque donnée reçue par l'API google
        const componentType = component.types[0];

        // Prendre les infos
        switch (componentType) {
            case "street_number":
                address1 = `${component.long_name} ${address1}`;
                break;
            case "route":
                address1 += component.short_name;
                break;
            case "postal_code":
                postalCode = component.long_name;
                break;
            case "locality":
                locality = component.long_name;
                break;
        }
    }

    // Remplir les informations dansl les champs
    document.querySelector("#user_input_autocomplete_address").value = address1.trim(); // Remplit le champ d'adresse
    document.querySelector("#locality").value = locality; // Remplit le champ de ville
    document.getElementById('preview-locality').textContent = locality;
    document.querySelector("#postal_code").value = postalCode; // Remplit le champ de code postal
    document.getElementById('preview-postal_code').textContent = postalCode;
}

google.maps.event.addDomListener(window, 'load', function () {
    initializeAutocomplete('user_input_autocomplete_address');
});
