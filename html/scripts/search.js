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
            tag.querySelector("span").textContent.trim()
        );
        applySearch(); // Met à jour le filtre lorsque les tags changent
    }

    // Assurez-vous que le conteneur des tags existe
    function ensureTagsContainerExists() {
        if (!tagsContainer) {
            const searchQuery = encodeURIComponent(searchInput.value.trim());
            var redirectUrl = `/?search=${searchQuery}`;
            if (window.location.href.includes("pro")) {
                redirectUrl = `/pro?search=${searchQuery}`;
            }
            window.location.href = redirectUrl;
        }
    }

    // Vérifiez les paramètres de recherche dans l'URL
    function checkForSearchParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get("search");
        if (searchQuery) {
            const tags = searchQuery.split(",").map((tag) => tag.trim());
            tags.forEach((tag) => {
                if (tag) {
                    addTag(tag);
                }
            });
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
            tag.querySelector("span").textContent.trim()
        );

        if (existingTags.includes(text.trim())) {
            return;
        }

        const tag = document.createElement("div");
        tag.className =
            "flex items-center gap-2 bg-secondary text-white px-3 py-1 rounded-full";
        tag.innerHTML = `<span>${text}</span><i class="fa-solid fa-times cursor-pointer"></i>`;

        tag.querySelector("i").addEventListener("click", () => {
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

    // Met à jour le menu déroulant
    function updateDropdown(value) {
        dropdownMenu.innerHTML = "";
        if (value.trim() !== "") {
            const item = document.createElement("div");
            item.className = "p-3 cursor-pointer hover:bg-base100";
            item.textContent = value;
            item.setAttribute("tabindex", "0");
            item.addEventListener("click", () => {
                addMultipleTags(value.trim());
                searchInput.value = "";
                dropdownMenu.classList.add("hidden");
            });
            dropdownMenu.appendChild(item);
        }

        recentTags.forEach((tag) => {
            const item = document.createElement("div");
            item.className =
                "p-3 cursor-pointer flex justify-between items-center hover:bg-base100";
            const textContainer = document.createElement("span");
            textContainer.textContent = tag;
            textContainer.className = "cursor-pointer flex-grow";

            const deleteIcon = document.createElement("i");
            deleteIcon.className =
                "fa-solid fa-times text-gray-400 hover:text-black ml-3 cursor-pointer";
            deleteIcon.addEventListener("click", (e) => {
                e.stopPropagation();
                removeRecentTag(tag);
                updateDropdown(searchInput.value);
            });

            item.appendChild(textContainer);
            item.appendChild(deleteIcon);
            item.addEventListener("click", () => {
                addTag(tag);
                searchInput.value = "";
                dropdownMenu.classList.add("hidden");
            });
            item.setAttribute("tabindex", "0");
            dropdownMenu.appendChild(item);
        });

        const suggestionsText = document.createElement("div");
        suggestionsText.className =
            "p-3 text-gray-500 text-sm bg-base100 border-t border-base200 cursor-default select-none";
        suggestionsText.textContent =
            "À savoir : Utiliser des virgules permet d'ajouter plusieurs tags d'un coup.";
        suggestionsText.setAttribute("tabindex", "-1");
        dropdownMenu.appendChild(suggestionsText);

        if (dropdownMenu.children.length === 1) {
            dropdownMenu.classList.add("hidden");
        } else {
            dropdownMenu.classList.remove("hidden");
        }
    }

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
            const activeElement = document.activeElement;
            if (
                activeElement &&
                activeElement.getAttribute("tabindex") === "0"
            ) {
                addMultipleTags(activeElement.textContent.trim());
                searchInput.value = "";
                dropdownMenu.classList.add("hidden");
            }
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
            focusableItems[nextIndex].focus();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            if (currentIndex === 0) {
                searchInput.focus();
            } else {
                const prevIndex =
                    (currentIndex - 1 + focusableItems.length) %
                    focusableItems.length;
                focusableItems[prevIndex].focus();
            }
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (
                activeElement &&
                activeElement.getAttribute("tabindex") === "0"
            ) {
                addMultipleTags(activeElement.textContent.trim());
                searchInput.value = "";
                dropdownMenu.classList.add("hidden");
            }
        }
    });

    function removeRecentTag(tag) {
        recentTags = recentTags.filter((t) => t !== tag);
    }

    function validateInput() {
        ensureTagsContainerExists();
        if (searchInput.value.trim() !== "") {
            addMultipleTags(searchInput.value.trim());
            searchInput.value = "";
            dropdownMenu.classList.add("hidden");
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
        const offres = document.querySelectorAll('.card');
        let anyVisible = false; // Variable pour suivre si une offre est visible
    
        offres.forEach((offre) => {
            // Vérifie les filtres actifs
            let matchesTag = (searchs.length === 0);
            if (offre.querySelector('.tags')) {
                const tags = offre.querySelector('.tags').textContent.split(',').map(tag => tag.trim());
                matchesTag = searchs.every(tag => tags.includes(tag));
            }

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

    applySearch();
});
