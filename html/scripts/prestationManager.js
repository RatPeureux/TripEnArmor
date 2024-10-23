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
            const incluse = document.getElementById('newPrestationIncluse');

            this.prestationsContainer.addPrestation(name.value, incluse.ariaChecked);

            name.value = '';
            incluse.ariaChecked = 'false';
            
            this.updatePrestations();
        })
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
            .sort((a, b) => a.prestation - b.prestation)
            .forEach(prestation => {
                const elementDiv = document.createElement('tr');
                
                const elementTitle = document.createElement('td');
                elementTitle.textContent = prestation.name;
                elementTitle.classList.add('text-lg', 'font-semibold', 'text-center', 'mb-2', 'text-secondary');
                elementDiv.appendChild(elementTitle);

                const elementPrestation = document.createElement('td');
                // Ajouter le svg d'inclusion/exclusion
                elementDiv.appendChild(elementPrestation);

                const elementRemove = document.createElement('td');
                const removeButton = document.createElement('div');
                removeButton.classList.add('h-max', 'w-full', 'cursor-pointer', 'flex', 'justify-center', 'items-center');

                removeButton.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' class='fill-rouge-logo rounded-lg border border-transparent p-1 hover:border hover:border-rouge-logo' width='32' height='32' viewBox='0 0 384 512'><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>";

                removeButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.prestationsContainer.removePrestation(prestation.prestation, prestation.name);
                    this.updateTarifs();
                });
                elementRemove.appendChild(removeButton);
                elementDiv.appendChild(elementRemove);

                // <div class="h-max w-full cursor-pointer flex justify-center items-center"
                // id="addPrestationButton">   
                //     <svg xmlns="http://www.w3.org/2000/svg" class="bg-secondary fill-white rounded-lg border border-transparent hover:border hover:bg-green-900 hover:border-green-900 focus:scale-[0.97]" width="32" height="32" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                // </div>

                this.prestationInput.appendChild(elementDiv);
            });
        }

    }
}

// Initialisation des tags
document.addEventListener('DOMContentLoaded', () => {
    new PrestationManager('prestations');
});
