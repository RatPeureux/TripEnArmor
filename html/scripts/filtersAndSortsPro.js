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
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Empêche le comportement par défaut
    
                // Sélectionne toutes les flèches et sections développables
                const allDevelopped = document.querySelectorAll('.developped');
                const allArrows = document.querySelectorAll('.arrow');
    
                // Alterne l'état de la section actuelle
                const isCurrentlyHidden = developped.classList.contains('hidden');
                arrow.classList.toggle('rotate-90', isCurrentlyHidden); // Ajuste la rotation uniquement si la section est cachée
                developped.classList.toggle('hidden', !isCurrentlyHidden); // Ajuste la visibilité
    
                // Ferme les autres sections
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
    
        // Vérifie si les éléments existent
        if (!leftInput || !rightInput) {
            console.warn(`Inputs with IDs "${leftInputId}" or "${rightInputId}" not found.`);
            return;
        }
    
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
    
        inputs.forEach((input) => {
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
        localisation: '', // Texte de localisation
        types: [], // Types d'offre séléctionnés
    };

    function filterOnCategories(device) {
        const checkboxes = document.querySelectorAll('#developped-f1-'+device+' input[type="checkbox"]');
    
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Mettre à jour les catégories sélectionnées
                filterState.categories = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.replace(/-tel|-tab/, ''));
    
                // Appliquer les filtres croisés
                applyFiltersPro();
            });
        });
    }

    function filterOnLocalisations(device) {
        const locInputElement = document.getElementById('localisation-'+device);
    
        locInputElement.addEventListener('input', () => {
            // Mettre à jour la localisation dans l'état global
            filterState.localisation = locInputElement.value.trim();
    
            // Appliquer les filtres croisés
            applyFiltersPro();
        });
    }

    function filterOnOfferTypes(device) {
        const checkboxes = document.querySelectorAll('#developped-f7-'+device+' input[type="checkbox"]');
    
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Mettre à jour les types d'offres sélectionnées
                filterState.types = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.id.replace(/-tel|-tab/, ''));
    
                // Appliquer les filtres croisés
                applyFiltersPro();
            });
        });
    }

    function applyFiltersPro() {
        const offres = document.querySelectorAll('.card');
        let anyVisible = false; // Variable pour suivre si une offre est visible

        console.log(filterState);
    
        offres.forEach((offre) => {
            const category = offre.querySelector('.categorie').textContent.trim().replace(", ", "").replace(" d'", "_").toLowerCase();
            const localisation = offre.querySelector('.localisation');
            const city = localisation.querySelector('p:nth-of-type(1)').textContent.trim();
            const code = localisation.querySelector('p:nth-of-type(2)').textContent.trim();
            const type = offre.querySelector('.type-offre').textContent.trim().toLowerCase();

            console.log(category);
    
            // Vérifie les filtres actifs
            const matchesCategory = filterState.categories.length === 0 || filterState.categories.includes(category);
            const matchesLocalisation = filterState.localisation === '' || code.includes(filterState.localisation) || city.includes(filterState.localisation);
            const matchesType = filterState.types.length === 0 || filterState.types.includes(type);

            // Appliquer les filtres croisés
            if (matchesCategory && matchesLocalisation && matchesType) {
                offre.classList.remove('!hidden');
                anyVisible = true; // Au moins une offre est visible
            } else {
                offre.classList.add('!hidden');
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
                message.classList.add('font-bold');
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

    syncInputs()

    filterOnCategories('tab');
    filterOnCategories('tel');
    filterOnLocalisations('tab');
    filterOnLocalisations('tel');
    filterOnOfferTypes('tab');
    filterOnOfferTypes('tel');

    applyFiltersPro();
});