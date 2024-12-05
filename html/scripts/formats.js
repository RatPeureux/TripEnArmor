// LES NUMÉROS DE TÉLÉPHONE
function formatTelephone(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    if (value.length > 10) {
        value = value.slice(0, 10);
    }
    const formattedValue = value
        .match(/.{1,2}/g)
        ?.join(' ')
        || '';
    input.value = formattedValue;
}
document.querySelectorAll('input#num_tel')?.forEach(telInput => {
    telInput.addEventListener('input', function () {
        formatTelephone(this);
    });
});

// LES CODES POSTAUX
function formatCodePostal(input) {
    let value = input.value.replace(/[^0-9A-Ba-b]/g, '');
    if (value.length > 5) {
        value = value.slice(0, 5);
    }
    input.value = value;
}
document.querySelectorAll('input#postal_code')?.forEach(codeInput => {
    codeInput.addEventListener('input', function () {
        formatCodePostal(this);
    });
});

// LES IBANS
function formatIban(input) {
    let value = input.value.replace(/[^A-Z0-9]/g, ''); // Supprime tout sauf les lettres majuscules et les chiffres
    const prefix = "FR76"; // Préfixe du pays (France)

    // Si la chaîne a moins de 4 caractères, on vide le champ et on le réinitialise avec le préfixe
    if (value.length < 4) {
        input.value = prefix;
        return;
    }

    // Si l'IBAN commence déjà par "FR76", on l'enlève pour éviter la duplication
    if (value.startsWith(prefix)) {
        value = value.substring(4); // Enlever "FR76" pour ne pas répéter
    }

    // Limiter la longueur de la valeur à 23 caractères maximum (car 4 caractères sont pour le préfixe FR76)
    if (value.length > 23) {
        value = value.substring(0, 23); // Limiter à 20 caractères pour le numéro de compte
    }

    // Reconstitue l'IBAN avec le préfixe et formatage en groupes de 4 caractères
    const formattedValue = (prefix + value).match(/.{1,4}/g)?.join(' ') || prefix;

    // Met à jour la valeur dans le champ input
    input.value = formattedValue;
}
document.querySelectorAll('input#iban')?.forEach(ibanInput => {
    ibanInput.addEventListener('input', function () {
        formatIban(this);
    });
});

// LES NUMÉROS DE SIREN (OU SIRET FIN BREF ÇA CASSE LES CORONES)
function formatSiren(input) {
    // Supprime tout ce qui n'est pas un chiffre
    let value = input.value.replace(/\D/g, '');

    // Limite à 14 caractères (9 pour le SIREN + 5 pour les caractères supplémentaires)
    value = value.substring(0, 14);

    // Ajoute les espaces tous les 3 chiffres pour les trois premiers groupes
    let formatted = value
        .replace(/(\d{3})(\d)/, '$1 $2') // Ajoute un espace après les 3 premiers chiffres
        .replace(/(\d{3}) (\d{3})(\d)/, '$1 $2 $3') // Ajoute un espace après les 6 premiers chiffres
        .replace(/(\d{3}) (\d{3}) (\d{3})(\d+)/, '$1 $2 $3 $4'); // Le reste (5 derniers caractères sans espace)

    // Met à jour la valeur de l'input avec le format correct
    input.value = formatted;
}
document.querySelectorAll('input#num_siren')?.forEach(sirenInput => {
    sirenInput.addEventListener('input', function () {
        formatSiren(this);
    });
});
