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

            emptyMessage.colSpan = 4;
            emptyMessage.classList.add('text-center', 'text-lg', 'text-secondary');
            emptyMessage.textContent = 'Aucun prix ajouté.';

            emptyRow.appendChild(emptyMessage);
            this.priceInput.appendChild(emptyRow);
        } else {
            this.pricesContainer["prices"]
            .sort((a, b) => a.positionInGrille - b.positionInGrille)
            .forEach(price => {
                const elementDiv = document.createElement('tr');

                const elementPosition = document.createElement('td');
                elementPosition.textContent = price.positionInGrille;
                elementPosition.classList.add('text-base');
                elementDiv.appendChild(elementPosition);
                
                const elementTitle = document.createElement('td');
                elementTitle.textContent = price.name;
                elementTitle.classList.add('text-lg', 'font-semibold', 'text-center', 'mb-2', 'text-secondary');
                elementDiv.appendChild(elementTitle);

                const elementPrice = document.createElement('td');
                elementPrice.textContent = price.price + ' €';
                elementPrice.classList.add('text-base');
                elementDiv.appendChild(elementPrice);

                const elementRemove = document.createElement('td');
                const removeButton = document.createElement('div');
                removeButton.classList.add('h-max', 'w-full', 'cursor-pointer', 'flex', 'justify-center', 'items-center');

                removeButton.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' class='fill-rouge-logo rounded-lg border border-transparent p-1 hover:border hover:bg-rouge-logo' width='32' height='32' viewBox='0 0 384 512'><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z'/></svg>";

                removeButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.pricesContainer.removePrice(price.price, price.name, price.positionInGrille);
                    this.updateTarifs();
                });
                elementRemove.appendChild(removeButton);
                elementDiv.appendChild(elementRemove);

                // <div class="h-max w-full cursor-pointer flex justify-center items-center"
                // id="addPriceButton">   
                //     <svg xmlns="http://www.w3.org/2000/svg" class="bg-secondary fill-white rounded-lg border border-transparent hover:border hover:bg-green-900 hover:border-green-900 focus:scale-[0.97]" width="32" height="32" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                // </div>

                this.priceInput.appendChild(elementDiv);
            });
        }

    }
}

// Initialisation des tags
document.addEventListener('DOMContentLoaded', () => {
    new PriceManager('grilleTarifaire');
});
