document.addEventListener("DOMContentLoaded", function() {
    // !!! SCRIPT POUR LE FONCTIONNEMENT DE LA BARRE DE RECHERCHE
    const searchInput = document.getElementById('search-field');
    const dropdownMenu = document.getElementById('search-menu');
    const searchBtn = document.getElementById('search-btn');
    const tagsContainer = document.getElementById('tags-container');
    const clearTagsBtn = document.getElementById('clear-tags-btn');

    // Liste des séparateurs
    const separators = [","];

    // Liste des tags récents
    let recentTags = [];

    // Vérifie si le bouton "Supprimer tout" doit être affiché
    function updateClearButtonVisibility() {
        if (tagsContainer.children.length === 0) {
            clearTagsBtn.classList.add('hidden');
        } else {
            clearTagsBtn.classList.remove('hidden');
        }
    }

    // Ajouter une étiquette
    function addTag(text) {
        const existingTags = Array.from(tagsContainer.children).map(tag =>
            tag.querySelector('span').textContent.trim()
        );

        if (existingTags.includes(text.trim())) {
            return; // Arrête l'exécution si le tag est déjà présent
        }

        const tag = document.createElement('div');
        tag.className = 'flex items-center gap-2 bg-secondary text-white px-3 py-1 rounded-full';
        tag.innerHTML = `
            <span>${text}</span>
            <i class="fa-solid fa-times cursor-pointer"></i>
        `;
        tag.querySelector('i').addEventListener('click', () => {
            tag.remove();
            updateClearButtonVisibility(); // Vérifie la visibilité du bouton
        });
        tagsContainer.appendChild(tag);
        updateClearButtonVisibility(); // Affiche le bouton si un tag est ajouté

        // Ajouter aux tags récents
        updateRecentTags(text.trim());
    }

    // Ajouter plusieurs tags en fonction des séparateurs
    function addMultipleTags(input) {
        let tags = [input]; // Liste initiale avec le texte brut

        // Diviser la chaîne en fonction des séparateurs
        separators.forEach(separator => {
            tags = tags.flatMap(tag => tag.split(separator).map(t => t.trim())); // Trim après séparation
        });

        // Ajouter chaque tag individuellement
        tags.forEach(tag => {
            if (tag.trim() !== "") {
                addTag(tag.trim());
            }
        });
    }

    // Mettre à jour l'historique des tags récents
    function updateRecentTags(tag) {
        // Supprimer l'ancien emplacement si le tag est déjà dans la liste
        recentTags = recentTags.filter(t => t !== tag);

        // Ajouter le tag en tête
        recentTags.unshift(tag);

        // Garder uniquement les 3 derniers
        if (recentTags.length > 3) {
            recentTags.pop();
        }
    }

    // Mettre à jour le menu déroulant
    function updateDropdown(value) {
        dropdownMenu.innerHTML = ''; // Effacez le contenu précédent
        
        // Ajouter l'élément de suggestion basé sur l'entrée (en premier)
        if (value.trim() !== '') {
            const item = document.createElement('div');
            item.className = 'p-3 cursor-pointer hover:bg-base100';
            item.textContent = value;
            item.setAttribute('tabindex', '0'); // Rendre l'élément focalisable
            item.addEventListener('click', () => {
                addMultipleTags(value.trim());
                searchInput.value = '';
                dropdownMenu.classList.add('hidden');
            });
        
            dropdownMenu.appendChild(item);
        }
    
        // Ajouter les tags récents
        recentTags.forEach(tag => {
            const item = document.createElement('div');
            item.className = 'p-3 cursor-pointer flex justify-between items-center hover:bg-base100';
            
            // Conteneur du texte du tag
            const textContainer = document.createElement('span');
            textContainer.textContent = tag;
            textContainer.className = 'cursor-pointer flex-grow'; // Pour que le clic fonctionne sur le texte
    
            // Icône de suppression
            const deleteIcon = document.createElement('i');
            deleteIcon.className = 'fa-solid fa-times text-gray-400 hover:text-black ml-3 cursor-pointer';
            deleteIcon.addEventListener('click', (e) => {
                e.stopPropagation(); // Empêche l'événement de clic sur l'élément parent
                removeRecentTag(tag); // Supprime le tag de la liste
                updateDropdown(searchInput.value); // Met à jour le menu déroulant
            });
    
            item.appendChild(textContainer);
            item.appendChild(deleteIcon);
    
            // Gestion du clic sur l'élément pour ajouter le tag
            item.addEventListener('click', () => {
                addTag(tag);
                searchInput.value = '';
                dropdownMenu.classList.add('hidden');
            });
    
            item.setAttribute('tabindex', '0'); // Rendre focalisable pour navigation au clavier
            dropdownMenu.appendChild(item);
        });
    
        // Ajouter le texte explicatif pour les suggestions (à la fin)
        const suggestionsText = document.createElement('div');
        suggestionsText.className = 'p-3 text-gray-500 text-sm bg-base100 border-t border-base200 cursor-default select-none';
        suggestionsText.textContent = "À savoir : Utiliser des virgules permet d'ajouter plusieurs tags d'un coup.";
        suggestionsText.setAttribute('tabindex', '-1'); // Rendre non focalisable
        dropdownMenu.appendChild(suggestionsText);
    
        // Afficher ou masquer le menu en fonction du contenu
        if (dropdownMenu.children.length === 1) { // Seul le texte explicatif est présent
            dropdownMenu.classList.add('hidden');
        } else {
            dropdownMenu.classList.remove('hidden');
        }
    }
    
    // Navigation avec les flèches
    dropdownMenu.addEventListener('keydown', (e) => {
        const focusableItems = Array.from(dropdownMenu.querySelectorAll('[tabindex="0"]'));
        const activeElement = document.activeElement;
        const currentIndex = focusableItems.indexOf(activeElement);

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextIndex = (currentIndex + 1) % focusableItems.length; // Navigation circulaire
            focusableItems[nextIndex].focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentIndex === 0) {
                // Si le premier élément est actif, revenir au champ de recherche
                searchInput.focus();
            } else {
                const prevIndex = (currentIndex - 1 + focusableItems.length) % focusableItems.length; // Navigation circulaire
                focusableItems[prevIndex].focus();
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            activeElement.click(); // Sélectionne l'élément actif
        }
    });

    // Supprimer un tag de la liste des tags récents
    function removeRecentTag(tag) {
        recentTags = recentTags.filter(t => t !== tag); // Supprime le tag
    }

    // Valider l'entrée et ajouter des tags
    function validateInput() {
        if (searchInput.value.trim() !== '') {
            addMultipleTags(searchInput.value.trim());
            searchInput.value = '';
            dropdownMenu.classList.add('hidden');
        }
    }

    // Supprimer tous les tags
    function clearTags() {
        tagsContainer.innerHTML = ''; // Supprime tous les tags
        searchInput.value = ''; // Vide le champ de recherche
        updateClearButtonVisibility(); // Vérifie la visibilité du bouton
    }

    // Événements
    searchInput.addEventListener('input', (e) => {
        updateDropdown(e.target.value);
        // Restez sur le champ de recherche après chaque saisie
        searchInput.focus();
    });

    // Valider via le clic sur l'icône de recherche
    searchBtn.addEventListener('click', () => validateInput());

    // Valider via la touche Entrée
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault(); // Empêche le comportement par défaut du formulaire si c'est un <form>
            validateInput();
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (dropdownMenu.children.length > 0) {
                dropdownMenu.children[0].focus(); // Met le focus sur le premier élément
            }
        }
    });

    // Supprimer tous les tags au clic sur le bouton
    clearTagsBtn.addEventListener('click', clearTags);

    // Cacher le menu déroulant quand on clique en dehors
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#open-search')) {
            dropdownMenu.classList.add('hidden');
        }
    });

    // Masquer le bouton au démarrage
    updateClearButtonVisibility();

    // !!! SCRIPT POUR LE FONCTIONNEMENT DU FILTRE DE RECHERCHE
});
