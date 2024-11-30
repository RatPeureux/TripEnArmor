document.addEventListener("DOMContentLoaded", function() {
    // !!! SCRIPT POUR LE FONCTIONNEMENT DE LA BARRE DE RECHERCHE
    const searchInput = document.getElementById('search-field');
    const dropdownMenu = document.getElementById('search-menu');
    const searchBtn = document.getElementById('search-btn');
    const tagsContainer = document.getElementById('tags-container');
    const clearTagsBtn = document.getElementById('clear-tags-btn');

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
    }

    // Mettre à jour le menu déroulant
    function updateDropdown(value) {
        dropdownMenu.innerHTML = ''; // Effacez le contenu précédent
        if (value.trim() === '') {
            dropdownMenu.classList.add('hidden');
            return;
        }
        dropdownMenu.classList.remove('hidden');

        // Créez l'élément du menu
        const item = document.createElement('div');
        item.className = 'p-3 cursor-pointer hover:bg-base100';
        item.textContent = `${value}`;
        item.setAttribute('tabindex', '0'); // Rendre l'élément focalisable
        item.addEventListener('click', () => {
            addTag(item.textContent);
            searchInput.value = '';
            dropdownMenu.classList.add('hidden');
        });

        dropdownMenu.appendChild(item);

        // Si des éléments sont présents, appliquez le focus au premier élément seulement si l'utilisateur
        // a navigué dans le menu via les touches fléchées.
    }

    // Valider l'entrée et ajouter un tag
    function validateInput() {
        if (searchInput.value.trim() !== '') {
            addTag(searchInput.value.trim());
            searchInput.value = '';
            dropdownMenu.classList.add('hidden');
        }
    }

    // Supprimer tous les tags
    function clearTags() {
        tagsContainer.innerHTML = ''; // Supprime tous les tags
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

    // Navigation dans le menu
    dropdownMenu.addEventListener('keydown', (e) => {
        const activeElement = document.activeElement;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextSibling = activeElement.nextElementSibling;
            if (nextSibling) {
                nextSibling.focus(); // Passe au prochain élément
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const previousSibling = activeElement.previousElementSibling;
            if (previousSibling) {
                previousSibling.focus(); // Passe à l'élément précédent
            } else {
                // Lorsque l'élément actif est le premier du menu, passer au champ de recherche
                searchInput.focus(); // Met le focus sur le champ de recherche
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            activeElement.click(); // Sélectionne l'élément actif
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
});
