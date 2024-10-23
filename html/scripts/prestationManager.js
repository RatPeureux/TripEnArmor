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
            console.log("Clicked");
            const name = document.getElementById('newPrestationName');
            const include = document.getElementById('newPrestationInclude');

            console.log(include.checked);
            
            if (name.value !== '') {
                this.prestationsContainer.addPrestation(name.value, include.checked);
                
                name.value = '';
                include.checked = false;
                
                this.updatePrestations();
            }
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
            .sort((a, b) => b.isIncluded - a.isIncluded)
            .sort((a, b) => b.name - a.name)
            .forEach(prestation => {
                const elementDiv = document.createElement('tr');
                
                const elementName = document.createElement('td');
                elementName.textContent = prestation.name;
                elementName.classList.add('text-lg', 'font-semibold', 'text-center', 'mb-2', 'text-secondary');
                elementDiv.appendChild(elementName);

                const elementInclude = document.createElement('td');
                elementInclude.classList.add('h-max', 'w-full', 'flex', 'justify-center', 'items-center', 'fill-secondary', 'rounded-full', 'bg-clip-content');
                elementInclude.innerHTML= `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->${prestation.isIncluded ? `<path d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>` : `<path d="M384 80c8.8 0 16 7.2 16 16l0 320c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16L48 96c0-8.8 7.2-16 16-16l320 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32z"/>`}</svg>`
                console.log(elementInclude);
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
