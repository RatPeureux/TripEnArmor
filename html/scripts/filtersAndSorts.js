document.addEventListener("DOMContentLoaded", function () {
    // !!! TOGGLE AFFICHANT/DÉPLIANT LES INTERFACES DE FILTRES/TRIS
    // Fonction pour configurer un bouton avec fermeture automatique au clic à l'extérieur
    function setupAutoClose(buttonId, sectionId) {
        const button = document.getElementById(buttonId);
        const section = document.getElementById(sectionId);

        if (button && section) {
            // Écouteur global pour fermer la section au clic à l'extérieur
            document.addEventListener('click', function (event) {
                // Vérifie si le clic est en dehors du bouton et de la section
                if (!section.contains(event.target) && !button.contains(event.target)) {
                    section.classList.add('md:hidden');
                    section.classList.remove('md:block');
                }
            });
        }
    }

    // Initialisation des boutons pour les sections de tri
    setupAutoClose('sort-button-tab', 'sort-section-tab');

    // Fonction pour configurer un bouton qui affiche ou masque une section
    function setupToggleTab(buttonId, sectionId) {
        const button = document.getElementById(buttonId); // Récupère le bouton par son ID
        const section = document.getElementById(sectionId); // Récupère la section par son ID

        if (button && section) { // Vérifie que les éléments existent
            button.addEventListener('click', function (event) { // Ajoute un événement au clic
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
            button.addEventListener('click', function (event) {
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
            button.addEventListener('click', function (event) {
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
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut

                // Alterne l'état de la section actuelle
                const isCurrentlyHidden = developped.classList.contains('hidden');
                arrow.classList.toggle('rotate-90', isCurrentlyHidden); // Ajuste la rotation uniquement si la section est cachée
                developped.classList.toggle('hidden', !isCurrentlyHidden); // Ajuste la visibilité

                // Ferme les autres sections
                document.querySelectorAll('.developped')?.forEach(section => {
                    if (section !== developped) {
                        section.classList.add('hidden'); // Cache toutes les autres sections
                    }
                });

                document.querySelectorAll('.arrow')?.forEach(icon => {
                    if (icon !== arrow) {
                        icon.classList.remove('rotate-90'); // Réinitialise la rotation des autres icônes
                    }
                });
            });
        }
    }

    // Initialisation des filtres pour les onglets (tablette et bureau)
    developpedFilter('button-f1-tab', 'arrow-f1-tab', 'developped-f1-tab');
    developpedFilter('button-f2-tab', 'arrow-f2-tab', 'developped-f2-tab');
    developpedFilter('button-f3-tab', 'arrow-f3-tab', 'developped-f3-tab');
    developpedFilter('button-f4-tab', 'arrow-f4-tab', 'developped-f4-tab');
    developpedFilter('button-f5-tab', 'arrow-f5-tab', 'developped-f5-tab');
    developpedFilter('button-f6-tab', 'arrow-f6-tab', 'developped-f6-tab');
    developpedFilter('button-f7-tab', 'arrow-f7-tab', 'developped-f7-tab');

    // Initialisation des filtres pour téléphone
    developpedFilterAutoClose('button-f1-tel', 'arrow-f1-tel', 'developped-f1-tel');
    developpedFilterAutoClose('button-f2-tel', 'arrow-f2-tel', 'developped-f2-tel');
    developpedFilterAutoClose('button-f3-tel', 'arrow-f3-tel', 'developped-f3-tel');
    developpedFilterAutoClose('button-f4-tel', 'arrow-f4-tel', 'developped-f4-tel');
    developpedFilterAutoClose('button-f5-tel', 'arrow-f5-tel', 'developped-f5-tel');
    developpedFilterAutoClose('button-f6-tel', 'arrow-f6-tel', 'developped-f6-tel');
    developpedFilterAutoClose('button-f7-tel', 'arrow-f7-tel', 'developped-f7-tel');


    // !!!
    function enforceDynamicBounds(leftInputId, rightInputId) {
        const leftInput = document.getElementById(leftInputId);
        const rightInput = document.getElementById(rightInputId);

        // Mettre à jour les limites à chaque modification
        function updateBounds() {
            leftInput.max = rightInput.value; // Le max de gauche est la valeur de droite
            rightInput.min = leftInput.value; // Le min de droite est la valeur de gauche
        }

        // Ajouter des écouteurs pour détecter les changements
        leftInput.addEventListener('input', () => {
            if (parseFloat(leftInput.value) > parseFloat(rightInput.value)) {
                leftInput.value = rightInput.value; // Ajuste la valeur si nécessaire
            }
            updateBounds();
        });

        rightInput.addEventListener('input', () => {
            if (parseFloat(rightInput.value) < parseFloat(leftInput.value)) {
                rightInput.value = leftInput.value; // Ajuste la valeur si nécessaire
            }
            updateBounds();
        });

        // Initialiser les bornes lors du chargement
        updateBounds();
    }

    // Appliquer la logique aux champs de note
    enforceDynamicBounds('min-note-tab', 'max-note-tab');
    enforceDynamicBounds('min-note-tel', 'max-note-tel');

    // Appliquer la logique aux champs de prix
    enforceDynamicBounds('min-price-tab', 'max-price-tab');
    enforceDynamicBounds('min-price-tel', 'max-price-tel');


    // !!! FILTRAGES DE DONNÉES
    // Fonction pour synchroniser les inputs entre tablette et téléphone
    function syncInputs() {
        // Récupère tous les inputs (checkbox, text, range, number, etc.)
        const inputs = Array.from(document.querySelectorAll('input'));

        inputs?.forEach((input) => {
            input.addEventListener('input', () => {
                // Synchronise avec les autres inputs ayant un ID similaire
                const baseId = input.id.replace(/-tel|-tab/, ''); // Supprime les suffixes spécifiques

                inputs.forEach((otherInput) => {
                    const otherBaseId = otherInput.id.replace(/-tel|-tab/, ''); // Supprime les suffixes pour comparer

                    if (baseId === otherBaseId && otherInput !== input) {
                        if (input.type === 'checkbox') {
                            otherInput.checked = input.checked;
                        } else {
                            otherInput.value = input.value;
                        }
                    }
                });
            });
        });
    }

    const filterState = {
        categories: [], // Catégories sélectionnées
        disponiblitees: [], // Disponibilitées sélectionnées
        localisation: '', // Texte de localisation
        note: ['0', '5'], // Note générale minimale et maximale
        prix: ['0', document.getElementById('max-price-tab').max], // Prix minimal et maximal
        gammes: [], // Gammes sélectionnées
    };

    function filterOnCategories(device) {
        const checkboxes = document.querySelectorAll('#developped-f1-' + device + ' input[type="checkbox"]');

        checkboxes?.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Mettre à jour les catégories sélectionnées
                filterState.categories = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.replace(/-tel|-tab/, ''));

                // Appliquer les filtres croisés
                applyFilters();
            });
        });
    }

    function filterOnAvailability(device) {
        const checkboxes = document.querySelectorAll('#developped-f2-' + device + ' input[type="checkbox"]');

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Mettre à jour les catégories sélectionnées
                filterState.disponiblitees = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.replace(/-tel|-tab/, ''));

                // Appliquer les filtres croisés
                applyFilters();
            });
        });
    }

    function filterOnLocalisations(device) {
        const locInputElement = document.getElementById('localisation-' + device);

        locInputElement.addEventListener('input', () => {
            // Mettre à jour la localisation dans l'état global
            filterState.localisation = locInputElement.value.trim();

            // Appliquer les filtres croisés
            applyFilters();
        });
    }

    function filterOnNotes(device) {
        const minNoteInputElement = document.getElementById('min-note-' + device);
        const maxNoteInputElement = document.getElementById('max-note-' + device);

        minNoteInputElement.addEventListener('input', () => {
            // Mettre à jour la localisation dans l'état global
            filterState.note[0] = minNoteInputElement.value.trim();

            // Appliquer les filtres croisés
            applyFilters();
        });

        maxNoteInputElement.addEventListener('input', () => {
            // Mettre à jour la localisation dans l'état global
            filterState.note[1] = maxNoteInputElement.value.trim();

            // Appliquer les filtres croisés
            applyFilters();
        });
    }

    function filterOnPrices(device) {
        const minPriceInputElement = document.getElementById('min-price-' + device);
        const maxPriceInputElement = document.getElementById('max-price-' + device);

        minPriceInputElement.addEventListener('input', () => {
            // Mettre à jour la localisation dans l'état global
            filterState.prix[0] = minPriceInputElement.value.trim();

            // Appliquer les filtres croisés
            applyFilters();
        });

        maxPriceInputElement.addEventListener('input', () => {
            // Mettre à jour la localisation dans l'état global
            filterState.prix[1] = maxPriceInputElement.value.trim();

            // Appliquer les filtres croisés
            applyFilters();
        });
    }

    function filterOnGammes(device) {
        const checkboxes = document.querySelectorAll('#developped-f6-' + device + ' input[type="checkbox"]');

        checkboxes?.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Mettre à jour les catégories sélectionnées
                filterState.gammes = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.replace(/-tel|-tab/, ''));

                // Appliquer les filtres croisés
                applyFilters();
            });
        });
    }

    function applyFilters() {
        const offres = document.querySelectorAll('.card');
        let anyVisible = false; // Variable pour suivre si une offre est visible

        offres?.forEach((offre) => {
            // Récupère les informations de l'offre
            const category = offre.querySelector('.categorie').textContent.replace(", ", "").replace(" d'", "_").toLowerCase().trim();
            const availability = offre.getAttribute('title').replace("é", "e").toLowerCase().trim();
            const city = offre.querySelector('.localisation').querySelector('p:nth-of-type(1)').textContent.trim();
            const code = offre.querySelector('.localisation').querySelector('p:nth-of-type(2)').textContent.trim();

            const note = offre.querySelector('.note');
            const price = offre.querySelector('.prix');

            // Vérifie les filtres actifs
            let matchesCategory = false;
            if (category) {
                matchesCategory = filterState.categories.length === 0 || filterState.categories.includes(category);
            }

            let matchesAvailability = false;
            if (availability) {
                matchesAvailability = filterState.disponiblitees.length === 0 || filterState.disponiblitees.includes(availability);
            }

            let matchesLocalisation = false;
            if (city && code) {
                matchesLocalisation = filterState.localisation === '' || code.includes(filterState.localisation) || city.includes(filterState.localisation);
            }

            let matchesNote = (filterState.note[0] === '0' && filterState.note[1] === '5');
            if (note) {
                matchesNote = filterState.note[0] <= note.getAttribute('title').trim() && note.getAttribute('title').trim() <= filterState.note[1];
            }

            let matchesPrice = (filterState.prix[0] === '0' && filterState.prix[1] === document.getElementById('max-price-tab').max);
            if (price) {
                if (price.getAttribute('title') !== "Gamme des prix") {
                    matchesPrice = (price.getAttribute('title').match(/Min (\d+),/)) ? filterState.prix[0] <= parseInt(price.getAttribute('title').match(/Min (\d+),/)[1], 10) && parseInt(price.getAttribute('title').match(/Min (\d+),/)[1], 10) <= filterState.prix[1] : false;
                } else {
                    matchesPrice = filterState.gammes.length === 0 || filterState.gammes.includes(price.textContent.trim());
                }
            }

            // Appliquer les filtres croisés
            if (matchesCategory && matchesAvailability && matchesLocalisation && matchesNote && matchesPrice) {
                offre.classList.remove('hidden');
                anyVisible = true; // Au moins une offre est visible
            } else {
                offre.classList.add('hidden');
            }
        });

        // Vérifie si aucune offre n'est visible
        const noMacthesElement = document.getElementById('no-matches-message'); // Element pour afficher un message
        if (!anyVisible) {
            if (!noMacthesElement) {
                // Crée et ajoute un élément de message si non présent
                const message = document.createElement('div');
                message.id = 'no-matches-message';
                message.textContent = 'Aucune offre ne correspond à vos critères.';
                message.classList.add('mt-4');
                message.classList.add('text-h2');
                document.querySelector('#no-matches').appendChild(message); // Ajouter dans le conteneur des offres
            }
        } else {
            // Supprime le message si des offres sont visibles
            if (noMacthesElement) {
                noMacthesElement.remove();
            }
        }
    }

    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);

        const category = urlParams.get('category');
        if (category) {

            const checkbox = document.querySelector(`input[id="${category}-tab"], input[id="${category}-tel"]`);
            if (checkbox) {
                checkbox.checked = true;
            }

            const checkboxes = document.querySelectorAll('#developped-f1-tab input[type="checkbox"], #developped-f1-tel input[type="checkbox"]');
            filterState.categories = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.id.replace(/-tel|-tab/, ''));
        }
    }

    initializeFiltersFromURL();

    syncInputs();

    filterOnCategories('tab');
    filterOnCategories('tel');
    filterOnAvailability('tab');
    filterOnAvailability('tel');
    filterOnLocalisations('tab');
    filterOnLocalisations('tel');
    filterOnNotes('tab');
    filterOnNotes('tel');
    filterOnPrices('tab');
    filterOnPrices('tel');
    filterOnGammes('tab');
    filterOnGammes('tel');

    applyFilters();
});