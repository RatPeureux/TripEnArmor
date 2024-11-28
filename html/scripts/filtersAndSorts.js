document.addEventListener("DOMContentLoaded", function() {
    // !!! TOGGLE AFFICHANT/DÉPLIANT LES INTERFACES DE FILTRES/TRIS
    // Fonction pour configurer un bouton qui affiche ou masque une section
    function setupToggleTab(buttonId, sectionId) {
        const button = document.getElementById(buttonId); // Récupère le bouton par son ID
        const section = document.getElementById(sectionId); // Récupère la section par son ID

        if (button && section) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) { // Ajoute un événement au clic
                event.preventDefault(); // Empêche le comportement par défaut (ex: navigation)
                // Alterne entre affichage (md:block) et masquage (md:hidden) de la section
                if (section.classList.contains('md:hidden')) {
                    section.classList.remove('md:hidden');
                    section.classList.add('md:block');
                } else {
                    section.classList.remove('md:block');
                    section.classList.add('md:hidden');
                }
            });
        }
    }

    // Initialisation des boutons pour les onglets (ex: filtres et tri)
    setupToggleTab('filter-button-tab', 'filter-section-tab');
    setupToggleTab('sort-button-tab', 'sort-section-tab');

    // Fonction pour configurer un bouton qui masque ou affiche une section en mode téléphone
    function setupToggleTel(buttonId, sectionId) {
        const button = document.getElementById(buttonId); // Récupère le bouton
        const section = document.getElementById(sectionId); // Récupère la section

        if (button && section) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut
                section.classList.toggle('hidden'); // Alterne la classe 'hidden'
            });
        }
    }

    // Initialisation des boutons pour téléphone (ex: filtres et tri)
    setupToggleTel('filter-button-tel', 'filter-section-tel');
    setupToggleTel('sort-button-tel', 'sort-section-tel');


    // !!! PERMET LE DÉVELOPPEMENT AU CLIC D'UNE BOÎTE DE FILTRE
    // Fonction pour gérer les filtres avec flèches et contenus développables
    function developpedFilter(buttonId, arrowId, developpedId) {
        const button = document.getElementById(buttonId); // Récupère le bouton
        const arrow = document.getElementById(arrowId); // Récupère l'icône flèche
        const developped = document.getElementById(developpedId); // Récupère la section développable

        if (button && arrow && developped) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut
                arrow.classList.toggle('rotate-90'); // Alterne la rotation de l'icône
                developped.classList.toggle('hidden'); // Alterne la visibilité de la section
            });
        }
    }

    // Fonction pour gérer les filtres avec flèches et contenus développables (referme les autres filtres ouverts)
    function developpedFilterAutoClose(buttonId, arrowId, developpedId) {
        const button = document.getElementById(buttonId); // Récupère le bouton
        const arrow = document.getElementById(arrowId); // Récupère l'icône flèche
        const developped = document.getElementById(developpedId); // Récupère la section développable

        if (button && arrow && developped) { // Vérifie que les éléments existent
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Empêche le comportement par défaut

                // Ferme toutes les autres sections développables
                const allDevelopped = document.querySelectorAll('.developped'); // Sélectionne toutes les sections développables
                const allArrows = document.querySelectorAll('.arrow'); // Sélectionne toutes les icônes flèches

                allDevelopped.forEach(section => {
                    if (section !== developped) {
                        section.classList.add('hidden'); // Cache toutes les autres sections
                    }
                });

                allArrows.forEach(icon => {
                    if (icon !== arrow) {
                        icon.classList.remove('rotate-90'); // Réinitialise la rotation des autres icônes
                    }
                });

                // Alterne l'état de la section cliquée
                arrow.classList.toggle('rotate-90'); // Alterne la rotation de l'icône de la section actuelle
                developped.classList.toggle('hidden'); // Alterne la visibilité de la section actuelle
            });
        }
    }

    // Initialisation des filtres pour les onglets (tablette et bureau)
    developpedFilter('button-f1-tab', 'arrow-f1-tab', 'developped-f1-tab');
    developpedFilter('button-f2-tab', 'arrow-f2-tab', 'developped-f2-tab');
    developpedFilter('button-f3-tab', 'arrow-f3-tab', 'developped-f3-tab');
    developpedFilter('button-f4-tab', 'arrow-f4-tab', 'developped-f4-tab');
    developpedFilter('button-f5-tab', 'arrow-f5-tab', 'developped-f5-tab');

    // Initialisation des filtres pour téléphone
    developpedFilterAutoClose('button-f1-tel', 'arrow-f1-tel', 'developped-f1-tel');
    developpedFilterAutoClose('button-f2-tel', 'arrow-f2-tel', 'developped-f2-tel');
    developpedFilterAutoClose('button-f3-tel', 'arrow-f3-tel', 'developped-f3-tel');
    developpedFilterAutoClose('button-f4-tel', 'arrow-f4-tel', 'developped-f4-tel');
    developpedFilterAutoClose('button-f5-tel', 'arrow-f5-tel', 'developped-f5-tel');


    // !!! GESTION DES DOUBLE SLIDERS
    // Contrôle de la position du curseur "from" en fonction de la valeur
    function controlFromInput(fromSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput); // Récupère les valeurs numériques
        fillSlider(fromInput, toInput, '#cccccc', '#0a77ec', controlSlider); // Met à jour l'apparence du slider

        if (from > to) { // Empêche que "from" dépasse "to"
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromSlider.value = from;
        }
    }

    // Contrôle de la position du curseur "to"
    function controlToInput(toSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#cccccc', '#0a77ec', controlSlider);
        setToggleAccessible(toInput); // Met à jour l'accessibilité visuelle

        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
        }
    }

    // Gère les changements du slider "from"
    function controlFromSlider(fromSlider, toSlider, fromInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#cccccc', '#0a77ec', toSlider);

        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromInput.value = from;
        }
    }

    // Gère les changements du slider "to"
    function controlToSlider(fromSlider, toSlider, toInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#cccccc', '#0a77ec', toSlider);
        setToggleAccessible(toSlider);

        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
            toSlider.value = from;
        }
    }

    // Parse les valeurs des sliders pour les convertir en nombres
    function getParsed(currentFrom, currentTo) {
        const from = parseFloat(currentFrom.value);
        const to = parseFloat(currentTo.value);

        return [from, to];
    }

    // Met à jour l'apparence du slider avec un dégradé
    function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
        const rangeDistance = to.max - to.min;
        const fromPosition = from.value - to.min;
        const toPosition = to.value - to.min;

        controlSlider.style.background = `linear-gradient(
            to right,
            ${sliderColor} 0%,
            ${sliderColor} ${(fromPosition) / (rangeDistance) * 100}%,
            ${rangeColor} ${(fromPosition) / (rangeDistance) * 100}%,
            ${rangeColor} ${(toPosition) / (rangeDistance) * 100}%, 
            ${sliderColor} ${(toPosition) / (rangeDistance) * 100}%, 
            ${sliderColor} 100%)`;
    }

    // Met à jour l'accessibilité en fonction de la valeur du slider
    function setToggleAccessible(currentTarget) {
        if (!currentTarget) return;
        const toSlider = currentTarget;
        if (Number(currentTarget.value) <= 0) {
            toSlider.classList.add('z-2'); // Ajoute une classe spécifique si nécessaire
        } else {
            toSlider.classList.remove('z-2');
        }
    }

    // Initialise les sliders avec leurs entrées associées
    function initializeSliderControls(sliderFromId, sliderToId, inputFromId, inputToId) {
        const fromSlider = document.querySelector(sliderFromId);
        const toSlider = document.querySelector(sliderToId);
        const fromInput = document.querySelector(inputFromId);
        const toInput = document.querySelector(inputToId);

        if (fromSlider && toSlider && fromInput && toInput) {
            fillSlider(fromSlider, toSlider, '#cccccc', '#0a77ec', toSlider);
            setToggleAccessible(toSlider);

            // Ajoute les événements pour synchroniser sliders et inputs
            fromSlider.oninput = () => controlFromSlider(fromSlider, toSlider, fromInput);
            toSlider.oninput = () => controlToSlider(fromSlider, toSlider, toInput);
            fromInput.oninput = () => controlFromInput(fromSlider, fromInput, toInput, toSlider);
            toInput.oninput = () => controlToInput(toSlider, fromInput, toInput, toSlider);
        } else {
            console.error(`Error initializing sliders: Check element IDs (${sliderFromId}, ${sliderToId}, ${inputFromId}, ${inputToId}).`);
        }
    }

    // Initialisation des sliders pour les différents critères (note et prix, par onglet et téléphone)
    initializeSliderControls('#from-slider-note-tab', '#to-slider-note-tab', '#from-input-note-tab', '#to-input-note-tab');
    initializeSliderControls('#from-slider-price-tab', '#to-slider-price-tab', '#from-input-price-tab', '#to-input-price-tab');

    initializeSliderControls('#from-slider-note-tel', '#to-slider-note-tel', '#from-input-note-tel', '#to-input-note-tel');
    initializeSliderControls('#from-slider-price-tel', '#to-slider-price-tel', '#from-input-price-tel', '#to-input-price-tel');

    // !!! FILTRAGES DE DONNÉES
    function filterOnCategories() {
        const checkboxes = document.querySelectorAll('#developped-f1-tab input[type="checkbox"]');
        
        const offres = document.querySelectorAll('.card');

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Créer un tableau pour stocker les types sélectionnés
                const selectedTypes = [];
                
                // Parcours des cases à cocher et ajout des types sélectionnés dans un tableau
                checkboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        selectedTypes.push(checkbox.id);
                    }
                });

                console.log(selectedTypes);

                // Affichage ou masquage des offres en fonction des types sélectionnés
                offres.forEach((offre) => {
                    const type = offre.querySelector('.type-offre').src.split('/').pop().replace('.jpg', '');
                    console.log(type);

                    // Si l'offre correspond à un des types sélectionnés ou si aucune case n'est cochée
                    if (selectedTypes.length === 0 || selectedTypes.includes(type)) {
                        offre.classList.remove('!hidden');
                    } else {
                        offre.classList.add('!hidden');
                    }
                });
            });
        });
    }

    function filterOnLocalisations() {
        const locInputElement = document.getElementById('loc');

        const offres = document.querySelectorAll('.card');
    
        locInputElement.addEventListener('input', () => {
            const locInput = locInputElement.value.trim();
    
            offres.forEach((offre) => {
                const localisationElement = offre.querySelector('.localisation');
                const code = localisationElement.querySelector('p:nth-of-type(2)').textContent.trim();
                const city = localisationElement.querySelector('p:nth-of-type(1)').textContent.trim();
    
                if (locInput === '' || code.includes(locInput) || city.includes(locInput)) {
                    offre.classList.remove('!hidden');
                } else {
                    offre.classList.add('!hidden');
                }
            });
        });
    }

    function filterOnGeneralRates() {
        
    }

    function filterOnPrices() {
        
    }

    filterOnCategories();
    filterOnLocalisations();
    filterOnGeneralRates();
    filterOnPrices();
});