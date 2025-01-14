document.addEventListener("DOMContentLoaded", function () {
    // !!! SCRIPT POUR LE FONCTIONNEMENT DE LA BARRE DE RECHERCHE
    const searchInput = document.getElementById("search-field");
    const dropdownMenu = document.getElementById("search-menu");
    const searchBtn = document.getElementById("search-btn");
    const tagsContainer = document.getElementById("tags-container");
    const clearTagsBtn = document.getElementById("clear-tags-btn");

    let searchs = []

    // Liste des séparateurs
    const separators = [","];
    // Liste des tags récents
    let recentTags = [];

    function syncSearchsWithTags() {
        searchs = Array.from(tagsContainer.children).map(tag =>
            tag.querySelector("span")?.textContent.trim()
        );
        applySearch(); // Met à jour le filtre lorsque les tags changent
    }

    // Vérifiez les paramètres de recherche dans l'URL
    function checkForSearchParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get("search");
        const categoryQuery = urlParams.get("category");

        if (searchQuery) {
            const tags = searchQuery.split(",").map((tag) => tag.trim());
            tags.forEach((tag) => {
                if (tag) {
                    addTag(tag);
                }
            });
        }

        if (categoryQuery) {
            const checkboxId1 = `${categoryQuery}-tab`;
            const checkbox1 = document.getElementById(checkboxId1);

            if (checkbox1) {
                checkbox1.checked = true;
            } else {
                console.warn(`Checkbox avec l'ID "${checkboxId1}" introuvable.`);
            }

            const checkboxId2 = `${categoryQuery}-tel`;
            const checkbox2 = document.getElementById(checkboxId2);

            if (checkbox2) {
                checkbox2.checked = true;
            } else {
                console.warn(`Checkbox avec l'ID "${checkboxId2}" introuvable.`);
            }
        }
    }

    // Met à jour la visibilité du bouton "Supprimer tout"
    function updateClearButtonVisibility() {
        if (tagsContainer) {
            if (tagsContainer.children.length === 0) {
                clearTagsBtn.classList.add("hidden");
            } else {
                clearTagsBtn.classList.remove("hidden");
            }
        }
    }

    // Ajoute un tag
    function addTag(text) {
        const existingTags = Array.from(tagsContainer.children).map((tag) =>
            tag.querySelector("span")?.textContent.trim()
        );

        if (existingTags.includes(text.trim())) {
            return;
        }

        const tag = document.createElement("div");
        tag.className =
            "flex items-center gap-2 bg-secondary text-white px-3 py-1 mb-4";
        tag.innerHTML = `<span>${text}</span><i class="fa-solid fa-times cursor-pointer"></i>`;

        tag.querySelector("i")?.addEventListener("click", () => {
            tag.remove();
            updateClearButtonVisibility();
            syncSearchsWithTags(); // Met à jour la liste searchs
        });

        tagsContainer.appendChild(tag);
        updateClearButtonVisibility();
        updateRecentTags(text.trim());
        syncSearchsWithTags(); // Met à jour la liste searchs
    }

    // Ajoute plusieurs tags
    function addMultipleTags(input) {
        let tags = [input];
        separators.forEach((separator) => {
            tags = tags.flatMap((tag) =>
                tag.split(separator).map((t) => t.trim())
            );
        });
        tags.forEach((tag) => {
            if (tag.trim() !== "") {
                addTag(tag.trim());
            }
        });
    }

    // Met à jour les tags récents
    function updateRecentTags(tag) {
        recentTags = recentTags.filter((t) => t !== tag);
        recentTags.unshift(tag);
        if (recentTags.length > 3) {
            recentTags.pop();
        }
    }

    // Liste combinée des tags
    const allTags = [
        'Culturel', 'Patrimoine', 'Histoire', 'Urbain', 'Nature', 
        'Plein air', 'Sport', 'Nautique', 'Gastronomie', 'Musée', 
        'Atelier', 'Musique', 'Famille', 'Cinéma', 'Cirque', 
        'Son et lumière', 'Humour', 'Française', 'Fruits de mer', 
        'Asiatique', 'Indienne', 'Italienne', 'Gastronomique', 
        'Restauration rapide', 'Crêperie', 'Exotique'
    ];

    // Vérifie la présence du tags-container
    function isTagsContainerAvailable() {
        return !!tagsContainer;
    }

    function updateDropdown(value) {
        dropdownMenu.innerHTML = "";
        if (value.trim() !== "") {
            const lastCommaIndex = value.lastIndexOf(',');
            const currentSearchTerm = lastCommaIndex !== -1 ? value.substring(lastCommaIndex + 1).trim() : value.trim();
            
            const relatedTags = allTags.filter(tag =>
                tag.toLowerCase().includes(currentSearchTerm.toLowerCase())
            );
    
            // Récupérer les tags déjà ajoutés pour éviter les doublons
            const existingTags = tagsContainer ? Array.from(tagsContainer.children).map(tag =>
                tag.querySelector("span")?.textContent.trim()
            ) : [];
    
            // Filtrer les tags déjà existants et ceux dans le champ de recherche
            const currentInputTags = value.split(',').map(tag => tag.trim());
            const filteredTags = relatedTags.filter(tag => 
                !existingTags.includes(tag) && !currentInputTags.includes(tag)
            );
    
            if (filteredTags.length > 0) {
                filteredTags.forEach((tag) => {
                    const item = document.createElement("div");
                    item.className = "p-3 cursor-pointer hover:bg-base100";
                    item.textContent = tag;
                    item.setAttribute("tabindex", "0");
    
                    const handleSelection = () => {
                        let currentText = searchInput.value;
                        const lastCommaIndex = currentText.lastIndexOf(',');
    
                        if (lastCommaIndex !== -1) {
                            currentText = currentText.slice(0, lastCommaIndex + 1) + ' ';
                        } else {
                            currentText = ''; // Si aucune virgule, on démarre un nouveau texte
                        }
    
                        currentText += `${tag}, `;  // Ajoute le tag avec une virgule
                        searchInput.value = currentText; // Met à jour le champ de recherche
                        searchInput.focus(); // Repositionne le curseur
                        dropdownMenu.classList.add("hidden"); // Masque le dropdown
                    };
    
                    item.addEventListener("click", handleSelection);
                    item.addEventListener("keydown", (e) => {
                        if (e.key === "Enter") {
                            handleSelection();
                        }
                    });
    
                    dropdownMenu.appendChild(item);
                });
            } else {
                const noMatchMessage = document.createElement("div");
                noMatchMessage.className = "p-3 text-rouge-logo text-sm bg-base100 cursor-default select-none";
                noMatchMessage.textContent = "Aucun tag correspondant.";
                dropdownMenu.appendChild(noMatchMessage);
            }
    
            const separatorInfo = document.createElement("div");
            separatorInfo.className = "p-3 text-gray-500 text-sm bg-base100 cursor-default select-none border-t border-base300";
            separatorInfo.textContent = "À savoir : Utiliser des virgules permet d'ajouter plusieurs tags d'un coup.";
            dropdownMenu.appendChild(separatorInfo);
    
            dropdownMenu.classList.toggle("hidden", dropdownMenu.children.length === 0);
        } else {
            dropdownMenu.classList.add("hidden");
        }
    }

    // Redirige vers une page avec le tag sélectionné
    function redirectToSearch(tag) {
        const searchQuery = encodeURIComponent(tag.trim());
        let redirectUrl = `/offres?search=${searchQuery}`;

        if (searchInput.placeholder.includes("des")) {
            if (searchInput.placeholder.includes("restaurant")) {
                redirectUrl += "&category=restauration";
            } else if (searchInput.placeholder.includes("spectacle")) {
                redirectUrl += "&category=spectacle";
            } else if (searchInput.placeholder.includes("activité")) {
                redirectUrl += "&category=activite";
            } else if (searchInput.placeholder.includes("visite")) {
                redirectUrl += "&category=visite";
            } else if (searchInput.placeholder.includes("attraction")) {
                redirectUrl += "&category=parc_attraction";
            }
        }

        if (window.location.href.includes("pro")) {
            redirectUrl = `/pro?search=${searchQuery}`;
        }

        window.location.href = redirectUrl;
    }

    searchBtn.addEventListener("click", () => validateInput());

    searchInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            validateInput();
        }
    });

    // Navigation avec les flèches
    searchInput.addEventListener("keydown", (e) => {
        if (e.key === "ArrowDown") {
            e.preventDefault();
            const firstItem = dropdownMenu.querySelector('[tabindex="0"]');
            if (firstItem) {
                firstItem.focus();
            }
        } else if (e.key === "Enter") {
            e.preventDefault();
            validateInput();
        }
    });

    dropdownMenu.addEventListener("keydown", (e) => {
        const focusableItems = Array.from(
            dropdownMenu.querySelectorAll('[tabindex="0"]')
        );
        const activeElement = document.activeElement;
        const currentIndex = focusableItems.indexOf(activeElement);

        if (e.key === "ArrowDown") {
            e.preventDefault();
            const nextIndex = (currentIndex + 1) % focusableItems.length;
            focusableItems[nextIndex]?.focus();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            if (currentIndex === 0) {
                // Si on est sur le premier élément, on remet le focus sur le champ de recherche
                searchInput.focus();
            } else {
                const prevIndex = (currentIndex - 1 + focusableItems.length) % focusableItems.length;
                focusableItems[prevIndex]?.focus();
            }
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (activeElement && activeElement.getAttribute("tabindex") === "0") {
                // Ajoute le tag au champ de recherche
                const selectedTag = activeElement.textContent.trim();
                handleSelection(selectedTag);
            }
        }
    });

    // Gestion de la validation de l'entrée
    function validateInput() {
        const value = searchInput.value.trim();
        if (value !== "") {
            if (isTagsContainerAvailable()) {
                addMultipleTags(value);
                // Vider le champ de recherche uniquement ici
                searchInput.value = ""; 
                dropdownMenu.classList.add("hidden");
            } else {
                redirectToSearch(value);
            }
        }
    }

    function clearTags() {
        tagsContainer.innerHTML = "";
        searchInput.value = "";
        updateClearButtonVisibility();
        syncSearchsWithTags(); // Met à jour la liste searchs
    }

    searchInput.addEventListener("input", (e) => {
        updateDropdown(e.target.value);
        searchInput.focus();
    });

    searchBtn.addEventListener("click", () => validateInput());

    searchInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            validateInput();
        }
    });

    clearTagsBtn.addEventListener("click", clearTags);

    document.addEventListener("click", (e) => {
        if (!e.target.closest("#open-search")) {
            dropdownMenu.classList.add("hidden");
        }
    });

    checkForSearchParams();

    // !!! SCRIPT POUR LE FONCTIONNEMENT DU FILTRE DE RECHERCHE
    function applySearch() {
        let anyVisible = false; // Variable pour suivre si une offre est visible

        document.querySelectorAll('.card')?.forEach((offre) => {
            // Vérifie les filtres actifs
            let matchesTag = (searchs.length === 0);
            const tags = offre.querySelector('.tags')?.textContent.split(',').map(tag => tag.trim());
            matchesTag = searchs.every(tag => tags?.includes(tag));

            // Appliquer les filtres croisés
            if (matchesTag) {
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
                message.textContent = 'Aucune offre ne possède les tags recherchés.';
                message.classList.add('mt-4');
                message.classList.add('text-h2');
                document.querySelector('#no-matches')?.appendChild(message); // Ajouter dans le conteneur des offres
            }
        } else {
            // Supprime le message si des offres sont visibles
            noMacthesElement?.remove();
        }
    }

    applySearch();
});