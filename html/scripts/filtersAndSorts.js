document.addEventListener("DOMContentLoaded", function () {
  // !!! TOGGLE AFFICHANT/DÉPLIANT LES INTERFACES DE FILTRES/TRIS

  // Fonction pour configurer un bouton avec fermeture automatique au clic à l'extérieur
  function setupAutoClose(buttonId, sectionId) {
    const button = document.getElementById(buttonId);
    const section = document.getElementById(sectionId);

    if (button && section) {
      // Écouteur global pour fermer la section au clic à l'extérieur
      document.addEventListener("click", function (event) {
        // Vérifie si le clic est en dehors du bouton et de la section
        if (!section.contains(event.target) && !button.contains(event.target)) {
          section.classList.add("md:hidden");
          section.classList.remove("md:block");
        }
      });
    }
  }

  // Initialisation des boutons pour les sections de tri
  setupAutoClose("sort-button-tab", "sort-section-tab");

  // Fonction pour configurer un bouton qui affiche ou masque une section
  function setupToggleTab(buttonId, sectionId) {
    const button = document.getElementById(buttonId); // Récupère le bouton par son ID
    const section = document.getElementById(sectionId); // Récupère la section par son ID

    if (button && section) {
      // Vérifie que les éléments existent
      button.addEventListener("click", function (event) {
        // Ajoute un événement au clic
        event.preventDefault(); // Empêche le comportement par défaut (ex: navigation)
        // Alterne entre affichage (md:block) et masquage (md:hidden) de la section
        if (section.classList.contains("md:hidden")) {
          section.classList.remove("md:hidden");
          section.classList.add("md:block");
        } else {
          section.classList.remove("md:block");
          section.classList.add("md:hidden");
        }
      });

      button.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
          event.preventDefault(); // Empêche le comportement par défaut (ex: navigation)
          // Alterne entre affichage (md:block) et masquage (md:hidden) de la section
          if (section.classList.contains("md:hidden")) {
            section.classList.remove("md:hidden");
            section.classList.add("md:block");
          } else {
            section.classList.remove("md:block");
            section.classList.add("md:hidden");
          }
        }
      });
    }
  }

  // Initialisation des boutons pour les onglets (ex: filtres et tri)
  setupToggleTab("filter-button-tab", "filter-section-tab");
  setupToggleTab("sort-button-tab", "sort-section-tab");

  // Fonction pour configurer un bouton qui masque ou affiche une section en mode téléphone
  function setupToggleTel(buttonId, sectionId) {
    const button = document.getElementById(buttonId); // Récupère le bouton
    const section = document.getElementById(sectionId); // Récupère la section

    if (button && section) {
      // Vérifie que les éléments existent
      button.addEventListener("click", function (event) {
        event.preventDefault(); // Empêche le comportement par défaut
        section.classList.toggle("hidden"); // Alterne la classe 'hidden'
      });
    }
  }

  // Initialisation des boutons pour téléphone (ex: filtres et tri)
  setupToggleTel("filter-button-tel", "filter-section-tel");
  setupToggleTel("sort-button-tel", "sort-section-tel");

  // !!! PERMET LE DÉVELOPPEMENT AU CLIC D'UNE BOÎTE DE FILTRE
  // Fonction pour gérer les filtres avec flèches et contenus développables
  function developpedFilter(buttonId, arrowId, developpedId) {
    const button = document.getElementById(buttonId); // Récupère le bouton
    const arrow = document.getElementById(arrowId); // Récupère l'icône flèche
    const developped = document.getElementById(developpedId); // Récupère la section développable

    if (button && arrow && developped) {
      // Vérifie que les éléments existent
      button.addEventListener("click", function (event) {
        event.preventDefault(); // Empêche le comportement par défaut
        arrow.classList.toggle("rotate-90"); // Alterne la rotation de l'icône
        developped.classList.toggle("hidden"); // Alterne la visibilité de la section
      });

      button.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
          event.preventDefault(); // Empêche le comportement par défaut
          arrow.classList.toggle("rotate-90"); // Alterne la rotation de l'icône
          developped.classList.toggle("hidden"); // Alterne la visibilité de la section
        }
      });
    }
  }

  // Fonction pour gérer les filtres avec flèches et contenus développables (referme les autres filtres ouverts)
  function developpedFilterAutoClose(buttonId, arrowId, developpedId) {
    const button = document.getElementById(buttonId); // Récupère le bouton
    const arrow = document.getElementById(arrowId); // Récupère l'icône flèche
    const developped = document.getElementById(developpedId); // Récupère la section développable

    if (button && arrow && developped) {
      // Vérifie que les éléments existent
      button.addEventListener("click", function (event) {
        event.preventDefault(); // Empêche le comportement par défaut

        // Alterne l'état de la section actuelle
        const isCurrentlyHidden = developped.classList.contains("hidden");
        arrow.classList.toggle("rotate-90", isCurrentlyHidden); // Ajuste la rotation uniquement si la section est cachée
        developped.classList.toggle("hidden", !isCurrentlyHidden); // Ajuste la visibilité

        // Ferme les autres sections
        document.querySelectorAll(".developped")?.forEach((section) => {
          if (section !== developped) {
            section.classList.add("hidden"); // Cache toutes les autres sections
          }
        });

        document.querySelectorAll(".arrow")?.forEach((icon) => {
          if (icon !== arrow) {
            icon.classList.remove("rotate-90"); // Réinitialise la rotation des autres icônes
          }
        });
      });
    }
  }

  // Initialisation des filtres pour les onglets (tablette et bureau)
  developpedFilter("button-f1-tab", "arrow-f1-tab", "developped-f1-tab");
  developpedFilter("button-f2-tab", "arrow-f2-tab", "developped-f2-tab");
  developpedFilter("button-f3-tab", "arrow-f3-tab", "developped-f3-tab");
  developpedFilter("button-f4-tab", "arrow-f4-tab", "developped-f4-tab");
  developpedFilter("button-f5-tab", "arrow-f5-tab", "developped-f5-tab");
  developpedFilter("button-f6-tab", "arrow-f6-tab", "developped-f6-tab");

  // Initialisation des filtres pour téléphone
  developpedFilterAutoClose("button-f1-tel", "arrow-f1-tel", "developped-f1-tel");
  developpedFilterAutoClose("button-f2-tel", "arrow-f2-tel", "developped-f2-tel");
  developpedFilterAutoClose("button-f3-tel", "arrow-f3-tel", "developped-f3-tel");
  developpedFilterAutoClose("button-f4-tel", "arrow-f4-tel", "developped-f4-tel");
  developpedFilterAutoClose("button-f5-tel", "arrow-f5-tel", "developped-f5-tel");
  developpedFilterAutoClose("button-f6-tel", "arrow-f6-tel", "developped-f6-tel");

  // !!!
  function enforceDynamicBounds(leftInputId, rightInputId) {
    const leftInput = document.getElementById(leftInputId);
    const rightInput = document.getElementById(rightInputId);

    // Mettre à jour les limites à chaque modification
    function updateBounds() {
      if (leftInput && rightInput) {
        leftInput.max = rightInput.value; // Le max de gauche est la valeur de droite
        rightInput.min = leftInput.value; // Le min de droite est la valeur de gauche
      }
    }

    // Ajouter des écouteurs pour détecter les changements
    leftInput?.addEventListener("input", () => {
      if (parseFloat(leftInput?.value) > parseFloat(rightInput?.value)) {
        leftInput.value = rightInput.value; // Ajuste la valeur si nécessaire
      }
      updateBounds();
    });

    rightInput?.addEventListener("input", () => {
      if (parseFloat(rightInput?.value) < parseFloat(leftInput?.value)) {
        rightInput.value = leftInput.value; // Ajuste la valeur si nécessaire
      }
      updateBounds();
    });

    // Initialiser les bornes lors du chargement
    updateBounds();
  }

  // Appliquer la logique aux champs de note
  enforceDynamicBounds("min-note-tab", "max-note-tab");
  enforceDynamicBounds("min-note-tel", "max-note-tel");

  // Appliquer la logique aux champs de prix
  enforceDynamicBounds("min-price-tab", "max-price-tab");
  enforceDynamicBounds("min-price-tel", "max-price-tel");

  // !!! FILTRAGES DE DONNÉES

  // Fonction pour synchroniser les inputs entre tablette et téléphone
  function syncInputs() {
    // Récupère tous les inputs (checkbox, text, range, number, etc.)
    const inputs = Array.from(document.querySelectorAll("input"));

    inputs?.forEach((input) => {
      input.addEventListener("input", () => {
        // Synchronise avec les autres inputs ayant un ID similaire
        const baseId = input.id.replace(/-tel|-tab/, ""); // Supprime les suffixes spécifiques

        inputs.forEach((otherInput) => {
          const otherBaseId = otherInput.id.replace(/-tel|-tab/, ""); // Supprime les suffixes pour comparer

          if (baseId === otherBaseId && otherInput !== input) {
            if (input.type === "checkbox") {
              otherInput.checked = input.checked;
            } else {
              otherInput.value = input.value;
            }
          }
        });
      });
    });
  }

  const maxPriceTab = document.getElementById("max-price-tab");

  const filterState = {
    categories: [], // Catégories sélectionnées
    disponiblitees: [], // Disponibilitées sélectionnées
    localisation: "", // Texte de localisation
    note: ["0", "5"], // Note générale minimale et maximale
    prix: ["0", maxPriceTab?.max], // Prix minimal et maximal
    gammes: [], // Gammes sélectionnées
    tags: [], // Tags sélectionnés
  };

  function updateCategoryParam() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get("category");

    // Vérifiez si la catégorie est décochée (simulé ici par une condition)
    const isCategoryUnchecked = true; // Remplacez par votre logique pour vérifier si décoché

    if (isCategoryUnchecked && categoryParam) {
      urlParams.delete("category"); // Supprimer la clé 'category' de l'URL

      // Mettre à jour l'URL sans recharger la page
      const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
      history.replaceState(null, "", newUrl);
    }
  }

  function filterOnCategories(device) {
    const checkboxes = document.querySelectorAll(
      "#developped-f1-" + device + ' input[type="checkbox"]'
    );

    checkboxes?.forEach((checkbox) => {
      checkbox.addEventListener("change", () => {
        if (new URLSearchParams(window.location.search).has("category")) {
          updateCategoryParam();
        }

        // Mettre à jour les catégories sélectionnées
        filterState.categories = Array.from(checkboxes)
          .filter((checkbox) => checkbox.checked)
          .map((checkbox) => checkbox.id.replace(/-tel|-tab/, ""));

        // Appliquer les filtres croisés
        applyFilters();
      });
    });
  }

  function filterOnAvailability(device) {
    const checkboxes = document.querySelectorAll(
      "#developped-f2-" + device + ' input[type="checkbox"]'
    );

    checkboxes?.forEach((checkbox) => {
      checkbox.addEventListener("change", () => {
        // Mettre à jour les catégories sélectionnées
        filterState.disponiblitees = Array.from(checkboxes)
          .filter((checkbox) => checkbox.checked)
          .map((checkbox) => checkbox.id.replace(/-tel|-tab/, ""));

        // Appliquer les filtres croisés
        applyFilters();
      });
    });
  }

  function filterOnLocalisations(device) {
    const locInputElement = document.getElementById("localisation-" + device);

    locInputElement?.addEventListener("input", () => {
      // Mettre à jour la localisation dans l'état global
      filterState.localisation = locInputElement?.value.toLowerCase().trim();

      // Appliquer les filtres croisés
      applyFilters();
    });
  }

  function filterOnNotes(device) {
    const minNoteInputElement = document.getElementById("min-note-" + device);
    const maxNoteInputElement = document.getElementById("max-note-" + device);

    minNoteInputElement?.addEventListener("input", () => {
      // Mettre à jour la localisation dans l'état global
      filterState.note[0] = minNoteInputElement?.value.trim();

      // Appliquer les filtres croisés
      applyFilters();
    });

    maxNoteInputElement?.addEventListener("input", () => {
      // Mettre à jour la localisation dans l'état global
      filterState.note[1] = maxNoteInputElement?.value.trim();

      // Appliquer les filtres croisés
      applyFilters();
    });
  }

  function filterOnPrices(device) {
    const minPriceInputElement = document.getElementById("min-price-" + device);
    const maxPriceInputElement = document.getElementById("max-price-" + device);

    minPriceInputElement?.addEventListener("input", () => {
      // Mettre à jour la localisation dans l'état global
      filterState.prix[0] = minPriceInputElement?.value.trim();

      // Appliquer les filtres croisés
      applyFilters();
    });

    maxPriceInputElement?.addEventListener("input", () => {
      // Mettre à jour la localisation dans l'état global
      filterState.prix[1] = maxPriceInputElement?.value.trim();

      // Appliquer les filtres croisés
      applyFilters();
    });
  }

  function filterOnGammes(device) {
    const checkboxes = document.querySelectorAll(
      "#developped-f6-" + device + ' input[type="checkbox"]'
    );

    checkboxes?.forEach((checkbox) => {
      checkbox.addEventListener("change", () => {
        // Mettre à jour les catégories sélectionnées
        filterState.gammes = Array.from(checkboxes)
          .filter((checkbox) => checkbox.checked)
          .map((checkbox) => checkbox.id.replace(/-tel|-tab/, ""));

        // Appliquer les filtres croisés
        applyFilters();
      });
    });
  }

  function applyFilters() {
    const offres = document.querySelectorAll(".card");
    let anyVisible = false; // Variable pour suivre si une offre est visible

    offres?.forEach((offre) => {
      // Récupère les informations de l'offre
      const category =
        offre
          ?.querySelector(".categorie")
          ?.textContent?.replace(", ", "")
          .replace(" d'", "_")
          .toLowerCase()
          .trim() ?? null;
      const availability =
        offre?.getAttribute("title")?.replace("é", "e").toLowerCase().trim() ??
        null;
      const city =
        offre
          ?.querySelector(".localisation")
          ?.querySelector("p:nth-of-type(1)")
          ?.textContent.toLowerCase()
          .trim() ?? null;
      const code =
        offre
          ?.querySelector(".localisation")
          ?.querySelector("p:nth-of-type(2)")
          ?.textContent.trim() ?? null;

      const note = offre?.querySelector(".note") ?? null;
      const price = offre?.querySelector(".prix") ?? null;

      const tags =
        offre
          ?.querySelector(".tags")
          ?.textContent?.split(",")
          .map((tag) => tag.trim()) ?? [];

      // Vérifie les filtres actifs
      let matchesCategory = false;
      if (category) {
        matchesCategory =
          filterState.categories.length === 0 ||
          filterState.categories.includes(category);
      }

      let matchesAvailability = false;
      if (availability) {
        matchesAvailability =
          filterState.disponiblitees.length === 0 ||
          filterState.disponiblitees.includes(availability);
      }

      let matchesLocalisation = false;
      if (city && code) {
        matchesLocalisation =
          filterState.localisation === "" ||
          code.includes(filterState.localisation) ||
          city.includes(filterState.localisation);
      }

      let matchesNote =
        filterState.note[0] === "0" && filterState.note[1] === "5";
      if (note && !matchesNote) {
        matchesNote =
          filterState.note[0] <= note.getAttribute("title").trim() &&
          note.getAttribute("title").trim() <= filterState.note[1];
      }

      let matchesPrice =
        filterState.prix[0] === "0" &&
        filterState.prix[1] === document.getElementById("max-price-tab")?.max;
      if (price && !matchesPrice) {
        if (price.getAttribute("title") !== "Gamme des prix") {
          matchesPrice = price.getAttribute("title").match(/Min (\d+),/)
            ? filterState.prix[0] <=
                parseInt(
                  price.getAttribute("title").match(/Min (\d+),/)[1],
                  10
                ) &&
              parseInt(
                price.getAttribute("title").match(/Min (\d+),/)[1],
                10
              ) <= filterState.prix[1]
            : false;
        } else {
          matchesPrice =
            filterState.gammes.length === 0 ||
            filterState.gammes.includes(price.textContent.trim());
        }
      }

      let matchesTag = false;
      if (tags) {
        matchesTag =
          filterState.tags.length === 0 ||
          filterState.tags.every((tag) => tags?.includes(tag));
      }

      // Appliquer les filtres croisés
      if (
        matchesCategory &&
        matchesAvailability &&
        matchesLocalisation &&
        matchesNote &&
        matchesPrice &&
        matchesTag
      ) {
        offre.classList.remove("hidden");
        anyVisible = true; // Au moins une offre est visible
      } else {
        offre.classList.add("hidden");
      }
    });

    // Vérifie si aucune offre n'est visible
    const noMacthesElement = document.getElementById("no-matches-message"); // Element pour afficher un message
    if (!anyVisible) {
      if (!noMacthesElement) {
        // Crée et ajoute un élément de message si non présent
        const message = document.createElement("div");
        message.id = "no-matches-message";
        message.textContent = "Aucune offre ne correspond à vos critères.";
        message.classList.add("mt-4");
        message.classList.add("text-2xl");
        document?.querySelector("#no-matches").appendChild(message); // Ajouter dans le conteneur des offres
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

    const category = urlParams.get("category");
    if (category) {
      const checkbox = document.querySelector(
        `input[id="${category}-tab"], input[id="${category}-tel"]`
      );
      if (checkbox) {
        checkbox.checked = true;
      }

      const checkboxes = document.querySelectorAll(
        '#developped-f1-tab input[type="checkbox"], #developped-f1-tel input[type="checkbox"]'
      );
      filterState.categories = Array.from(checkboxes)
        .filter((checkbox) => checkbox.checked)
        .map((checkbox) => checkbox.id.replace(/-tel|-tab/, ""));
    }
  }

  // !!! FILTRE : BARRE DE RECHERCHE
  const searchInput = document.getElementById("search-field");
  const dropdownMenu = document.getElementById("search-menu");
  const searchBtn = document.getElementById("search-btn");
  const tagsContainer = document.getElementById("tags-container");
  const clearTagsBtn = document.getElementById("clear-tags-btn");

  // Liste combinée des tags
  const allTags = [
    "Culturel",
    "Patrimoine",
    "Histoire",
    "Urbain",
    "Nature",
    "Plein air",
    "Sport",
    "Nautique",
    "Gastronomie",
    "Musée",
    "Atelier",
    "Musique",
    "Famille",
    "Cinéma",
    "Cirque",
    "Son et lumière",
    "Humour",
    "Française",
    "Fruits de mer",
    "Asiatique",
    "Indienne",
    "Italienne",
    "Gastronomique",
    "Restauration rapide",
    "Crêperie",
    "Exotique",
  ];

  // Liste des séparateurs
  const separators = [","];

  // Vérifie la présence du tags-container
  function isTagsContainerAvailable() {
    return !!tagsContainer;
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

  function updateSearchParam(removedTag) {
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get("search");

    if (searchParam) {
      // Diviser les tags, les filtrer et recréer l'URL sans le tag supprimé
      const tags = searchParam.split(",").map((tag) => tag.trim());
      const updatedTags = tags.filter((tag) => tag !== removedTag);

      if (updatedTags.length > 0) {
        urlParams.set("search", updatedTags.join(","));
      } else {
        urlParams.delete("search"); // Supprimer le paramètre s'il est vide
      }

      if (urlParams.get("search") === "") {
        urlParams.delete("search"); // Supprimer le paramètre s'il est vide
      }

      // Mettre à jour l'URL sans recharger la page
      const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
      history.replaceState(null, "", newUrl);
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
      "flex items-center gap-2 bg-secondary text-white px-2 py-1 mb-4";
    tag.innerHTML = `<span>${text}</span><i class="fa-solid fa-times cursor-pointer"></i>`;

    tag.querySelector("i")?.addEventListener("click", () => {
      let tagIndex = filterState.tags.indexOf(text.trim());

      if (tagIndex > -1) {
        filterState.tags.splice(tagIndex, 1);
      }

      tag.remove();
      updateSearchParam(text.trim());
      updateClearButtonVisibility();

      applyFilters();
    });

    filterState.tags.push(text.trim());

    tagsContainer.appendChild(tag);
    updateClearButtonVisibility();

    applyFilters();
  }

  // Ajoute plusieurs tags
  function addMultipleTags(input) {
    let tags = [input];

    separators.forEach((separator) => {
      tags = tags.flatMap((tag) => tag.split(separator).map((t) => t.trim()));
    });

    tags.forEach((tag) => {
      if (tag.trim() !== "") {
        addTag(tag.trim());
      }
    });
  }

  function updateDropdown(value) {
    dropdownMenu.innerHTML = "";
    if (value.trim() !== "") {
      const lastCommaIndex = value.lastIndexOf(",");
      const currentSearchTerm =
        lastCommaIndex !== -1
          ? value.substring(lastCommaIndex + 1).trim()
          : value.trim();

      const relatedTags = allTags.filter((tag) =>
        tag.toLowerCase().includes(currentSearchTerm.toLowerCase())
      );

      const existingTags = tagsContainer
        ? Array.from(tagsContainer.children).map((tag) =>
            tag.querySelector("span")?.textContent.trim()
          )
        : [];

      // Filtrer les tags déjà ajoutés uniquement pour éviter les doublons dans la liste déroulante
      const filteredTags = relatedTags.filter(
        (tag) => !existingTags.includes(tag)
      );

      if (filteredTags.length > 0) {
        filteredTags.forEach((tag) => {
          const item = document.createElement("div");
          item.className = "p-3 cursor-pointer hover:bg-base100";
          item.textContent = tag;
          item.setAttribute("tabindex", "0");

          const handleSelection = () => {
            let currentText = searchInput.value;
            const lastCommaIndex = currentText.lastIndexOf(",");

            if (lastCommaIndex !== -1) {
              currentText = currentText.slice(0, lastCommaIndex + 1) + " ";
            } else {
              currentText = "";
            }

            currentText += `${tag}, `;
            searchInput.value = currentText.trim(); // Met à jour le champ de recherche
            searchInput.focus();
            dropdownMenu.classList.add("hidden");
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
        noMatchMessage.className =
          "p-3 text-rouge-logo text-sm bg-base100 cursor-default select-none";
        noMatchMessage.textContent = "Aucun tag correspondant.";
        dropdownMenu.appendChild(noMatchMessage);
      }

      const separatorInfo = document.createElement("div");
      separatorInfo.className =
        "p-3 text-gray-500 text-sm bg-base100 cursor-default select-none border-t border-base300";
      separatorInfo.textContent =
        "À savoir : Utiliser des virgules permet d'ajouter plusieurs tags d'un coup.";
      dropdownMenu.appendChild(separatorInfo);

      dropdownMenu.classList.toggle(
        "hidden",
        dropdownMenu.children.length === 0
      );
    } else {
      dropdownMenu.classList.add("hidden");
    }
  }

  function clearTags() {
    filterState.tags.splice(0, filterState.tags.length);

    tagsContainer.innerHTML = "";
    searchInput.value = "";
    history.replaceState(
      null,
      "",
      window.location.pathname +
        "?" +
        new URLSearchParams(
          [...new URLSearchParams(window.location.search)].filter(
            ([key]) => key !== "search"
          )
        ).toString()
    );
    updateClearButtonVisibility();

    applyFilters();
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
        const prevIndex =
          (currentIndex - 1 + focusableItems.length) % focusableItems.length;
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

  if (isTagsContainerAvailable()) {
    initializeFiltersFromURL();
    checkForSearchParams();

    syncInputs();

    filterOnCategories("tab");
    filterOnCategories("tel");
    filterOnAvailability("tab");
    filterOnAvailability("tel");
    filterOnLocalisations("tab");
    filterOnLocalisations("tel");
    filterOnNotes("tab");
    filterOnNotes("tel");
    filterOnPrices("tab");
    filterOnPrices("tel");
    filterOnGammes("tab");
    filterOnGammes("tel");

    applyFilters();
  }
});
