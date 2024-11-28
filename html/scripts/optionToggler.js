const activityTypeHTMLElement = document.getElementById("activityType");

const activityTypes = {
    get: (key) => {
        switch (key) {
            case 'activite':
                return 'optionActivite';
            case 'restauration':
                return 'optionRestauration';
            case 'visite':
                return 'optionVisite';
            case 'spectacle':
                return 'optionSpectacle';
            case 'parc_attraction':
                return 'optionParcAttraction';
            default:
                return null;
        }
    },
    activite: 'optionActivite',
    restauration: 'optionRestauration',
    visite: 'optionVisite',
    spectacle: 'optionSpectacle',
    parc_attraction: 'optionParcAttraction'
}

const show = (classToShow) => {
    const options = document.getElementsByClassName(classToShow);

    for (const option of options) {
        option.classList.remove("hidden");

        const childElements = option.querySelectorAll('input:not([id*="horaires"]):not([id="newPrixName"]):not([id="newPrixValeur"]):not([id="newPrestationName"]):not([type="checkbox"]), textarea');
        childElements.forEach(child => {
            child.required = true;
        });
    }
}

const hide = () => {
    for (const key in activityTypes) {
        const options = document.getElementsByClassName(activityTypes.get(key));

        for (const option of options) {
            option.classList.add('hidden');

            const childElements = option.querySelectorAll('input:not([type="checkbox"]), textarea');
            childElements.forEach(child => {
                child.required = false;
            });
        }
    }
}

activityType.addEventListener('change', () => {
    hide()
    switch (activityTypeHTMLElement.value) {
        case 'activite':
            show(activityTypes.activite);
            break;
        case 'restauration':
            show(activityTypes.restauration);
            break;
        case 'visite':
            show(activityTypes.visite);
            break;
        case 'spectacle':
            show(activityTypes.spectacle);
            break;
        case 'parc_attraction':
            show(activityTypes.parc_attraction);
            break;
        default:
            break;
    };
})