class PriceManager {
    constructor(inputId) {
        this.priceInput = document.getElementById(inputId);
        this.pricesContainer = {
            removePrice(price, name, positionInGrille) {
                this["prices"].pop({
                    price: price,
                    name: name,
                    positionInGrille: positionInGrille
                });
            },
            addPrice(price, name, positionInGrille) {
                this["prices"].push({
                    price: price,
                    name: name,
                    positionInGrille: positionInGrille
                });
            },
            size() {
                return this["prices"].length;
            },
            "prices" : [
                // {
                //     price: 10,
                //     name: "Tarif normal",
                //     positionInGrille: 1
                // },
                // {
                //     price: 5,
                //     name: "Tarif réduit",
                //     positionInGrille: 2
                // },
                // {
                //     price: 3,
                //     name: "Tarif enfant",
                //     positionInGrille: 3
                // },
                // {
                //     price: 10,
                //     name: "Tarif normal",
                //     positionInGrille: 1
                // },
                // {
                //     price: 5,
                //     name: "Tarif réduit",
                //     positionInGrille: 2
                // }
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
            const positionInGrille = document.getElementById('newPrixPosition');

            this.pricesContainer.addPrice(price.value, name.value, positionInGrille.value);

            price.value = '';
            name.value = '';
            positionInGrille.value = '';
            
            this.updateTarifs();
        })
    }

    updateTarifs() {
        this.priceInput.innerHTML = '';
        if (this.pricesContainer.size() === 0) {
            const emptyRow = document.createElement('tr');

            const emptyMessage = document.createElement('td');

            emptyMessage.colSpan = 2;
            emptyMessage.textContent = 'Aucun prix ajouté.';
            emptyMessage.classList.add('text-gray-500', 'text-sm', 'w-full', 'flex', 'flex-row', 'justify-center', 'items-center', 'p-8', 'col-span-3');
            emptyMessage.id = 'emptyMessage';

            emptyRow.appendChild(emptyMessage);
            this.priceInput.appendChild(emptyRow);
        } else {
            this.pricesContainer["prices"]
            .sort((a, b) => a.positionInGrille - b.positionInGrille)
            .forEach(price => {
                const elementDiv = document.createElement('tr');
                elementDiv.classList.add('relative', 'flex', 'flex-col', 'items-center', 'justify-center', 'p-4', 'border', 'border-secondary', 'rounded-lg', 'mb-4', 'w-full', "h-fit");
                
                const elementTitle = document.createElement('td');
                elementTitle.textContent = price.name;
                elementTitle.classList.add('text-lg', 'font-semibold', 'text-center', 'mb-2', 'w-full', 'text-secondary');
                elementDiv.appendChild(elementTitle);

                const elementPrice = document.createElement('td');
                elementPrice.textContent = price.price + ' €';
                elementPrice.classList.add('text-base');
                elementDiv.appendChild(elementPrice);

                const removeButton = document.createElement('td');
                removeButton.textContent = '✖';
                removeButton.classList.add('absolute', 'top-0', 'right-0', 'm-1', 'text-red-500', 'hover:text-red-700', 'cursor-pointer');
                removeButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.pricesContainer.removePrice(price.price, price.name, price.positionInGrille);
                    this.updateTarifs();
                });
                elementDiv.appendChild(removeButton);

                this.priceInput.appendChild(elementDiv);
            });
        }

    }
}

// Initialisation des tags
document.addEventListener('DOMContentLoaded', () => {
    new PriceManager('grilleTarifaire');
});
