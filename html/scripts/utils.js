// Activer tous les yeux
function bindEyeWithPasswordField(eye, pswdField) {
    eye.addEventListener('click', function () {
        if (pswdField.type === 'password') {
            pswdField.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            pswdField.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
}
Array.from(document.getElementsByClassName('eye-toggle-password')).forEach((eye) => {
    const champMdp = eye.previousElementSibling;
    bindEyeWithPasswordField(eye, champMdp);
});

// Copier dans le presse-papiers lorsque l'on clique sur l'élément
function copyToClipboard(el) {
    // Sélectionner le contenu du paragraphe
    const content = el.textContent.trim();

    // Copier le contenu dans le presse-papiers
    navigator.clipboard.writeText(content).then(() => {
        alert("Clé API copiée dans le presse-papiers !");
    }).catch(err => {
        console.error('Erreur lors de la copie : ', err);
    });
}
window.copyToClipboard = copyToClipboard;

// Dire à des champs (liste ou HTMLCollection) d'activer un bouton d'enregistrement des modifications
// lorsque au moins un de ces derniers change (et qu'il n'a pas sa valeur initiale)
function triggerSaveBtnOnInputsChange(inputs, saveBtn) {
    // Garder les valeurs initiales de chaque input (dans l'ordre)
    const initialValues = Array.from(inputs).map(input => input.value);

    // Tester le changement sur chaque input
    Array.from(inputs).forEach((input) => {
        input.addEventListener("input", () => {
            const hasChanges = Array.from(inputs).some((input, i) => input.value !== initialValues[i]);

            if (hasChanges) {
                saveBtn.disabled = false;
                saveBtn.classList.remove("opacity-50");
            } else {
                saveBtn.disabled = true;
                saveBtn.classList.add("opacity-50");
            }
        });
    });
}
window.triggerSaveBtnOnInputsChange = triggerSaveBtnOnInputsChange;

// Afficher un toast de notification
let notificationCount = 0;
function displayNotification(message) {
    
    // Créer l'élément notif
    const toast = document.createElement('div');
    toast.className = 'fixed right-5 bg-secondary text-white p-4 z-50 border border-white flex items-center gap-4';
    toast.textContent = message;

    // La croix pour enlever la notif au cas où
    const closeButton = document.createElement('button');
    closeButton.className = 'text-white font-bold text-2xl';
    closeButton.textContent = '×';

    toast.appendChild(closeButton);

    // Empiler les notifs
    const verticalPosition = 5 + notificationCount * 70;
    toast.style.bottom = `${verticalPosition}px`;

    toast.style.transform = 'translateX(100%)';
    toast.style.opacity = '0';
    toast.style.transition = 'transform 0.5s ease, opacity 0.5s ease';

    // Afficher la notif
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);

    notificationCount++;

    // Cacher la notif si croix cliquée
    closeButton.addEventListener('click', () => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(toast);
            notificationCount--;
        }, 500);
    });

    // Cacher la notif après 5 secondes
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(toast);
                notificationCount--;
            }, 500);
        }
    }, 5000);
}
window.displayNotification = displayNotification;

// Confirmation de déconnexion
function confirmLogout() {
    const confirmation = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    // Si l'utilisateur confirme, l'action par défaut (déconnexion) continue
    return confirmation;
}
window.confirmLogout = confirmLogout;

// Confirmation pour supprimer son compte
export function confirmDelete() {
    const confirmation = confirm(
        "Êtes-vous sûr de vouloir supprimer votre compte ?"
    );
    // Si l'utilisateur confirme, l'action par défaut (suppression) continue
    return confirmation;
}
window.confirmDelete = confirmDelete;

// Eviter de pouvoir mettre des dates d'expérience ultérieures au jour actuel
function setMaxDateExperience() {
    const select_date = document.getElementById("date_experience");
    if (select_date) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const maxDate = `${year}-${month}-${day}`;
    
        select_date.setAttribute("max", maxDate);
    }
}
window.onload = () => {
    setMaxDateExperience();
}

// Montrer / cacher le menu
function toggleMenu() {
    document.querySelector("#menu>div")?.classList.toggle("active");
    document.querySelector("#layer-background")?.classList.toggle("active");
}
window.toggleMenu = toggleMenu;

// Montrer / cacher les filtres
function toggleFiltres() {
    document.querySelector("#filtres")?.classList.toggle("active");
    document
        .querySelector("#layer-background-filtres")
        ?.classList.toggle("active");
}
window.toggleFiltres = toggleFiltres;

// Configurer les flèches pour faire des dropdown menus
function setupToggle(arrowID, buttonID, infoID) {
    const button = document.getElementById(buttonID);
    const arrow = document.getElementById(arrowID);
    const info = document.getElementById(infoID);
    arrow.classList.toggle('rotate-90');

    if (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            arrow.classList.toggle('rotate-90');
            info.classList.toggle('hidden');
        });
    }
}
window.setupToggle = setupToggle;

// Envoyer un pouce rouge ou pouce bleu à un avis
function sendReaction(idAvis, action) {
    const thumbDown = document.getElementById('thumb-down-' + idAvis);
    const thumbUp = document.getElementById('thumb-up-' + idAvis);
    const dislikeCountElement = document.getElementById(`dislike-count-${idAvis}`);
    const likeCountElement = document.getElementById(`like-count-${idAvis}`);

    // Réinitialisation des icônes
    thumbDown.classList.remove('fa-solid', 'text-rouge-logo');
    thumbDown.classList.add('fa-regular');

    thumbUp.classList.remove('fa-solid', 'text-secondary');
    thumbUp.classList.add('fa-regular');

    // Restauration des événements onclick par défaut
    thumbDown.onclick = function () {
        sendReaction(idAvis, 'down'); // Nouvelle action
    };

    thumbUp.onclick = function () {
        sendReaction(idAvis, 'up'); // Nouvelle action
    };

    // Gestion de la réaction "down"
    if (action === 'down' || action === 'upTOdown') {
        thumbDown.classList.remove('fa-regular');
        thumbDown.classList.add('fa-solid', 'text-rouge-logo');

        // Incrémentation du compteur de dislikes
        const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
        dislikeCountElement.textContent = currentDislikes + 1;

        // Décrémentation du compteur de likes si l'utilisateur change de réaction
        if (action === 'upTOdown') {
            const currentLikes = parseInt(likeCountElement.textContent) || 0;
            likeCountElement.textContent = currentLikes - 1;
        }

        // Mise à jour des événements onclick
        thumbDown.onclick = function () {
            sendReaction(idAvis, 'downTOnull'); // Nouvelle action pour annuler
        };

        thumbUp.onclick = function () {
            sendReaction(idAvis, 'downTOup'); // Nouvelle action
        };
    }

    // Gestion de la réaction "up"
    if (action === 'up' || action === 'downTOup') {
        thumbUp.classList.remove('fa-regular');
        thumbUp.classList.add('fa-solid', 'text-secondary');

        // Incrémentation du compteur de likes
        const currentLikes = parseInt(likeCountElement.textContent) || 0;
        likeCountElement.textContent = currentLikes + 1;

        // Décrémentation du compteur de dislikes si l'utilisateur change de réaction
        if (action === 'downTOup') {
            const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
            dislikeCountElement.textContent = currentDislikes - 1;
        }

        // Mise à jour des événements onclick
        thumbUp.onclick = function () {
            sendReaction(idAvis, 'upTOnull'); // Nouvelle action pour annuler
        };

        thumbDown.onclick = function () {
            sendReaction(idAvis, 'upTOdown'); // Nouvelle action
        };
    }

    if (action === 'upTOnull') {
        const currentLikes = parseInt(likeCountElement.textContent) || 0;
        likeCountElement.textContent = currentLikes - 1;
    }

    if (action === 'downTOnull') {
        const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
        dislikeCountElement.textContent = currentDislikes - 1;
    }

    // Envoi de la requête pour mettre à jour la réaction
    const url = `/scripts/thumb.php?id_avis=${idAvis}&action=${action}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const resultDiv = document.getElementById(`reaction-result-${idAvis}`);
            if (data.success) {
                resultDiv.innerHTML = `Réaction mise à jour : ${data.message}`;
            } else {
                resultDiv.innerHTML = `Erreur : ${data.message}`;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête:', error);
        });
}
window.sendReaction = sendReaction;

// Fonctions pratiques pour visualiser les images dans les avis
function openImageModal(id_avis, etatImageModal, index, imagesAvis) {
    etatImageModal.currentIndex = index;
    document.getElementById('imageModal' + id_avis).classList.remove('hidden');
    document.getElementById('modalImage' + id_avis).src = 'public/images/avis/' + imagesAvis[etatImageModal.currentIndex];
}
window.openImageModal = openImageModal;

function prevImage(id_avis, etatImageModal, imagesAvis) {
    etatImageModal.currentIndex = (etatImageModal.currentIndex > 0) ? etatImageModal.currentIndex - 1 : imagesAvis.length - 1;
    document.getElementById('modalImage' + id_avis).src = 'public/images/avis/' + imagesAvis[etatImageModal.currentIndex];
}
window.prevImage = prevImage;

function nextImage(id_avis, etatImageModal, imagesAvis) {
    etatImageModal.currentIndex = (etatImageModal.currentIndex < imagesAvis.length - 1) ? etatImageModal.currentIndex + 1 : 0;
    document.getElementById('modalImage' + id_avis).src = 'public/images/avis/' + imagesAvis[etatImageModal.currentIndex];
}
window.nextImage = nextImage;

function closeImageModal(id_avis) {
    document.getElementById('imageModal' + id_avis).classList.add('hidden');
}
window.closeImageModal = closeImageModal;