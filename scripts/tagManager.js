// Liste de tags culturels
const culturalTags = [
    'Culturel', 'Patrimoine', 'Histoire', 'Urbain', 'Nature',
    'Plein air', 'Sport', 'Nautique', 'Gastronomie', 'Musée',
    'Atelier', 'Musique', 'Famille', 'Cinéma', 'Cirque',
    'Son et lumière', 'Humour'
];

// Liste de tags cuisine
const cuisineTags = [
    'Française', 'Fruits de mer', 'Asiatique', 'Indienne', 
    'Italienne', 'Gastronomique', 'Restauration rapide', 
    'Crêperie'
];

class TagManager {
    constructor(inputId, containerId, suggestionListId) {
        this.tagInput = document.getElementById(inputId);
        this.tagContainer = document.getElementById(containerId);
        this.suggestionList = document.getElementById(suggestionListId);
        this.availableTags = []; // Pour stocker les tags disponibles
        this.addedTagsAct = new Set(); // Pour stocker les tags ajoutés
        this.addedTags = new Set(); // Pour stocker les tags ajoutés
        this.addedTags = new Set(); // Pour stocker les tags ajoutés
        this.addedTags = new Set(); // Pour stocker les tags ajoutés
        this.addedTags = new Set(); // Pour stocker les tags ajoutés

        this.init();
    }

    init() {
        this.updateSuggestionList();
        this.suggestionList.classList.add('hidden'); // La liste est cachée au départ
        this.tagContainer.classList.add('hidden'); // Cacher le conteneur de tags au départ

        this.tagInput.addEventListener('focus', () => {
            this.updateSuggestionList();
            this.suggestionList.classList.remove('hidden'); // Afficher la liste au focus
        });

        this.tagInput.addEventListener('input', () => {
            this.updateSuggestionList(); // Mettre à jour la liste lors de la saisie
        });

        document.addEventListener('click', (event) => {
            if (!this.tagInput.contains(event.target) && !this.suggestionList.contains(event.target)) {
                this.suggestionList.classList.add('hidden'); // Cacher la liste si on clique ailleurs
            }
        });

        this.suggestionList.addEventListener('click', (event) => {
            if (event.target.classList.contains('suggestion-item')) {
                const tag = event.target.getAttribute('data-tag');
                this.addTag(tag);
                this.tagInput.value = '';
                this.suggestionList.classList.add('hidden'); // Cacher la liste après ajout
            }
        });

        document.getElementById('activityType').addEventListener('change', (event) => {
            this.changeAvailableTags(event.target.value);
        });
    }

    changeAvailableTags(activityType) {
        switch (activityType) {
            case 'activite':
                this.availableTags = culturalTags;
                this.addedTags = []; // Pour stocker les tags ajoutés

                break;
            case 'visite':
                this.availableTags = culturalTags;
                break;
            case 'spectacle':
                this.availableTags = culturalTags;
                break;
            case 'parc_attraction':
                this.availableTags = culturalTags;
                break;
            case 'restauration':
                this.availableTags = cuisineTags;
                break;
            default:
                this.availableTags = []; // Aucune option sélectionnée
                break;
        }
        this.updateSuggestionList(); // Mettre à jour la liste après changement
    }

    updateSuggestionList() {
        this.suggestionList.innerHTML = ''; // Vider la liste actuelle
        const limitedTags = this.availableTags.filter(tag => !this.addedTags.has(tag)).slice(0, 5); // Limiter à 5 éléments
        limitedTags.forEach(tag => {
            const listItem = document.createElement('li');
            listItem.textContent = tag;
            listItem.classList.add('suggestion-item', 'p-2', 'cursor-pointer', 'hover:bg-gray-200');
            listItem.setAttribute('data-tag', tag);
            this.suggestionList.appendChild(listItem);
        });

        // Afficher la liste si elle contient des éléments
        this.suggestionList.classList.toggle('hidden', this.suggestionList.children.length === 0);
    }

    addTag(tag) {
        if (this.addedTags.has(tag)) {
            alert("Ce tag a déjà été ajouté.");
            return;
        }

        const tagDiv = document.createElement('div');
        tagDiv.textContent = tag;
        tagDiv.classList.add('tag', 'bg-green-900', 'text-white', 'py-1', 'px-3', 'rounded-full', 'mr-2', 'flex', 'items-center');

        const removeBtn = document.createElement('span');
        removeBtn.textContent = 'X';
        removeBtn.classList.add('remove-tag', 'ml-8', 'cursor-pointer');
        removeBtn.onclick = () => {
            this.tagContainer.removeChild(tagDiv);
            this.addedTags.delete(tag);
            this.updateSuggestionList();
            this.toggleTagContainerVisibility(); // Vérifier la visibilité du conteneur
        };

        tagDiv.appendChild(removeBtn);
        this.tagContainer.appendChild(tagDiv);
        this.addedTags.add(tag);
        this.updateSuggestionList();
        this.toggleTagContainerVisibility(); // Vérifier la visibilité du conteneur
    }

    toggleTagContainerVisibility() {
        if (this.addedTags.size > 0) {
            this.tagContainer.classList.remove('hidden'); // Afficher le conteneur si des tags sont présents
        } else {
            this.tagContainer.classList.add('hidden'); // Cacher le conteneur s'il n'y a pas de tags
        }
    }
}

// Initialisation des tags
document.addEventListener('DOMContentLoaded', () => {
    new TagManager('tag-input', 'tag-container', 'suggestion-list');
});
