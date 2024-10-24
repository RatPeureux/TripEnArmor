class PriceManager {
    constructor(inputId) {
        this.priceInput = document.getElementById(inputId);
        this.pricesContainer = {
            removePrice(price, name) {
                this["prices"] = this["prices"].filter(p => p.price != price || p.name != name);  // Supprime l'entrée correspondante
            },
            addPrice(price, name) {
                this["prices"].push({
                    price: price,
                    name: name
                });
            },
            size() {
                return this["prices"].length;
            },
            "prices" : [
            ],
        };

        this.init();
    }

    init() {
        this.updateTarifs();

        // Ajouter un prix
        const addPriceButton = document.getElementById('addPriceButton');
        
        addPriceButton.addEventListener('click', () => {
            const price = document.getElementById('newPrixValeur');
            const name = document.getElementById('newPrixName');

            if (price.value !== '' && name.value !== '') {
                this.pricesContainer.addPrice(price.value, name.value);

                price.value = '';
                name.value = '';

                this.updateTarifs();
            }
        });

        // Capture l'événement de soumission du formulaire pour ajouter les prix
        const form = document.getElementById('formulaire');
        form.addEventListener('submit', (e) => {
            this.addPricesToForm();
        });
    }

    updateTarifs() {
        this.priceInput.innerHTML = '';
        if (this.pricesContainer.size() === 0) {
            const emptyRow = document.createElement('tr');
            const emptyMessage = document.createElement('td');

            emptyMessage.colSpan = 4;
            emptyMessage.classList.add('text-center', 'text-lg', 'text-secondary');
            emptyMessage.textContent = 'Aucun prix ajouté.';

            emptyRow.appendChild(emptyMessage);
            this.priceInput.appendChild(emptyRow);
        } else {
            this.pricesContainer["prices"]
            .sort((a, b) => a.price - b.price)
            .forEach(price => {
                const elementDiv = document.createElement('tr');
                
                const elementTitle = document.createElement('td');
                elementTitle.id = 'name';
                elementTitle.textContent = price.name;
                elementTitle.classList.add('text-lg', 'font-semibold', 'text-center', 'mb-2', 'text-secondary');
                elementDiv.appendChild(elementTitle);

                const elementPrice = document.createElement('td');
                elementPrice.id = 'value';
                elementPrice.textContent = price.price + ' €';
                elementPrice.classList.add('text-base');
                elementDiv.appendChild(elementPrice);

                const elementRemove = document.createElement('td');
                const removeButton = document.createElement('div');
                removeButton.classList.add('h-max', 'w-full', 'cursor-pointer', 'flex', 'justify-center', 'items-center');

                removeButton.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' class='fill-rouge-logo rounded-lg border border-transparent p-1 hover:border hover:border-rouge-logo' width='32' height='32' viewBox='0 0 384 512'><path d='M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>";

                removeButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.pricesContainer.removePrice(price.price, price.name);
                    this.updateTarifs();
                });
                elementRemove.appendChild(removeButton);
                elementDiv.appendChild(elementRemove);

                this.priceInput.appendChild(elementDiv);
            });
        }
    }

    addPricesToForm() {
        // Supprime les anciens champs cachés pour éviter les duplications
        const oldFields = document.querySelectorAll('.price-input');
        oldFields.forEach(field => field.remove());

        const form = document.getElementById('formulaire');

        // Ajoute les prix comme champs cachés dans le formulaire
        this.pricesContainer["prices"].forEach((price, index) => {
            const hiddenName = document.createElement('input');
            hiddenName.type = 'hidden';
            hiddenName.name = `prices[${index}][name]`;
            hiddenName.value = price.name;
            hiddenName.classList.add('price-input');
            form.appendChild(hiddenName);

            const hiddenValue = document.createElement('input');
            hiddenValue.type = 'hidden';
            hiddenValue.name = `prices[${index}][value]`;
            hiddenValue.value = price.price;
            hiddenValue.classList.add('price-input');
            form.appendChild(hiddenValue);
        });
    }
}

// Initialisation des tags
document.addEventListener('DOMContentLoaded', () => {
    new PriceManager('grilleTarifaire');
});
