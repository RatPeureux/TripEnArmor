// Initialiser l'OTP et transmettre (une fois) les codes secrets
function loadTOTP() {
    // Afficher le loader pendant le chargement
    $('#loading-indicator').show();

    // Désactiver le bouton pendant le chargement
    $('#load-totp-btn').prop('disabled', true);

    $.ajax({
        url: '/scripts/get_totp.php',
        type: 'GET',
        data: {},

        // Si on a une réponse
        success: function (response) {
            if (response) {
                let data = JSON.parse(response);
                try {
                    $('#totp-container').append("<p class='w-full'>Votre code secret TOTP : <span class='font-bold' id='secret-span'>" + data.secret + "</span></p>");
                    $('#totp-container').append("<br>");
                    $('#totp-container').append("<p>Scannez ce QR code avec votre application d'authentification OTP : </p><img src=" + data.qr_code_uri + ">");
                    document.getElementById('confirm-totp-div').classList.remove('hidden');
                } catch (e) {
                    console.log(e);
                }

                $('#load-totp-btn').prop('disabled', true).text('');
                document.getElementById('load-totp-btn').classList.add('hidden');
            } else {
                $('#totp-container').append('Erreur lors de la réception des données TOTP');
            }
        },

        // A la fin de la requête
        complete: function () {
            // Masquer le loader après la requête
            $('#loading-indicator').hide();
            // Réactiver le bouton après la requête (que ce soit réussi ou non)
            $('#load-totp-btn').prop('disabled', false);
        }
    });
}
window.loadTOTP = loadTOTP;

// Confirmer l'OTP en BDD
function confirmTOTP() {

    // Afficher le loader pendant le chargement
    $('#loading-indicator-confirm').show();

    // Désactiver le bouton pendant le chargement
    $('#confirm-totp-btn').prop('disabled', true);

    $.ajax({
        url: '/scripts/confirm_totp.php',
        type: 'GET',
        data: {
            secret: document.getElementById('confirmTOTP').value
        },

        // Si on a une réponse
        success: function (response) {
            let data = JSON.parse(response);
            try {
                $('#div-for-totp').append("<p class='text-green-400'>" + data.message + "</p>");
                document.getElementById('totp-container').classList.add('hidden');
                document.getElementById('confirm-totp-div').classList.add('hidden');
            } catch (e) {
                console.log(e);
            }

            $('#confirm-totp-btn').prop('disabled', true).text('');
            document.getElementById('confirm-totp-btn').classList.add('hidden');
        },

        error: function (xhr, status, error) {
            console.log('Erreur : ' + error);
            console.log('Statut : ' + status);
            console.log('Réponse : ' + xhr.responseText);
        },

        // A la fin de la requête
        complete: function () {
            // Masquer le loader après la requête
            $('#loading-indicator').hide();
            // Réactiver le bouton après la requête (que ce soit réussi ou non)
            $('#load-totp-btn').prop('disabled', false);
        }
    });
}
window.confirmTOTP = confirmTOTP;
