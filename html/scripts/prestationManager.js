class PrestationManager {
    constructor(inputId) {
        this.prestationInput = document.getElementById(inputId);
        this.prestationsContainer = {
            removePrestation(name, isIncluded) {
                this["prestations"].pop({
                    name: name,
                    isIncluded: isIncluded
                });
            },
            addPrestation(name, isIncluded) {
                this["prestations"].push({
                    name: name,
                    isIncluded: isIncluded
                });
            },
            size() {
                return this["prestations"].length;
            },
            "prestations" : [
            ],
        };

        this.init();
    }

    init() {
        this.updatePrestations();

        // Ajouter une prestation
        const addPrestationButton = document.getElementById('addPrestationButton');
        
        addPrestationButton.addEventListener('click', () => {
            const name = document.getElementById('newPrestationName');
            const include = document.getElementById('newPrestationInclude');
            
            if (name.value !== '') {
                this.prestationsContainer.addPrestation(name.value, include.checked);
                
                name.value = '';
                
                this.updatePrestations();
            }
        })

        // Capture l'événement de soumission du formulaire pour ajouter les prix
        const form = document.getElementById('formulaire');
        form.addEventListener('submit', (e) => {
            this.addPrestationsToForm();
        });
    }

    updatePrestations() {
        this.prestationInput.innerHTML = '';
        if (this.prestationsContainer.size() === 0) {
            const emptyRow = document.createElement('tr');

            const emptyMessage = document.createElement('td');

            emptyMessage.colSpan = 4;
            emptyMessage.classList.add('text-center', 'text-lg', 'text-secondary');
            emptyMessage.textContent = 'Aucune prestation ajouté.';

            emptyRow.appendChild(emptyMessage);
            this.prestationInput.appendChild(emptyRow);
        } else {
            this.prestationsContainer["prestations"]
            .sort((a, b) => b.isIncluded - a.isIncluded)
            .sort((a, b) => (0 + a.name) - (0 + b.name))
            .forEach(prestation => {
                const elementDiv = document.createElement('tr');
                // INTERESSE BAPTISTE
                
                const elementName = document.createElement('td');
                elementName.textContent = prestation.name;
                elementName.classList.add('text-lg', 'font-semibold', 'text-center', 'mb-2', 'text-secondary');
                elementDiv.appendChild(elementName);

                const elementInclude = document.createElement('td');
                elementInclude.classList.add('h-max', 'w-full', 'flex', 'justify-center', 'items-center', prestation.isIncluded ? 'fill-secondary' : 'text-rouge-logo', 'rounded-full', 'bg-clip-content');
                elementInclude.innerHTML= prestation.isIncluded ? 
                `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>`
                :
                `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-x"><rect width="28" height="28" x="2" y="2" rx="4" ry="4"/><path d="m24 8-16 16"/><path d="m8 8 16 16"/></svg>`
                // INTERESSE PLUS BAPTISTE

                elementDiv.appendChild(elementInclude);

                const elementRemove = document.createElement('td');
                const removeButton = document.createElement('div');
                removeButton.classList.add('h-max', 'w-full', 'cursor-pointer', 'flex', 'justify-center', 'items-center');

                removeButton.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' class='fill-rouge-logo rounded-lg border border-transparent p-1 hover:border hover:border-rouge-logo' width='32' height='32' viewBox='0 0 384 512'><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>";

                removeButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.prestationsContainer.removePrestation(prestation.prestation, prestation.name);
                    this.updatePrestations();
                });
                elementRemove.appendChild(removeButton);
                elementDiv.appendChild(elementRemove);

                this.prestationInput.appendChild(elementDiv);
            });
        }
    }

    addPrestationsToForm() {
        // Supprime les anciens champs cachés pour éviter les duplications
        const oldFields = document.querySelectorAll('.prestations-input');
        oldFields.forEach(field => field.remove());

        const form = document.getElementById('formulaire');

        // Ajoute les prix comme champs cachés dans le formulaire
        this.prestationsContainer["prestations"].forEach((prestation, index) => {
            const hiddenName = document.createElement('input');
            hiddenName.type = 'hidden';
            hiddenName.name = `prestations[${index}][name]`;
            hiddenName.value = prestation.name;
            hiddenName.classList.add('prestations-input');
            form.appendChild(hiddenName);

            const hiddenValue = document.createElement('input');
            hiddenValue.type = 'hidden';
            hiddenValue.name = `prestations[${index}][value]`;
            hiddenValue.value = prestation.isIncluded;
            hiddenValue.classList.add('prestations-input');
            form.appendChild(hiddenValue);
        });
    }
}

// Initialisation des tags
document.addEventListener('DOMContentLoaded', () => {
    new PrestationManager('prestations');
});
