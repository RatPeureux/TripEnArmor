// Configurer les flèches pour faire des dropdown menus
function setupToggle(arrowID, buttonID, infoID) {
  const button = document.getElementById(buttonID);
  const arrow = document.getElementById(arrowID);
  const info = document.getElementById(infoID);
  arrow.classList.toggle("rotate-90");

  if (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      arrow.classList.toggle("rotate-90");
      info.classList.toggle("hidden");
    });
  }
}
window.setupToggle = setupToggle;

// Envoyer un pouce rouge ou pouce bleu à un avis
function sendReaction(idAvis, action) {
  const thumbDown = document.getElementById("thumb-down-" + idAvis);
  const thumbUp = document.getElementById("thumb-up-" + idAvis);
  const dislikeCountElement = document.getElementById(
    `dislike-count-${idAvis}`
  );
  const likeCountElement = document.getElementById(`like-count-${idAvis}`);

  // Réinitialisation des icônes
  thumbDown.classList.remove("fa-solid", "text-rouge-logo");
  thumbDown.classList.add("fa-regular");

  thumbUp.classList.remove("fa-solid", "text-secondary");
  thumbUp.classList.add("fa-regular");

  // Restauration des événements onclick par défaut
  thumbDown.onclick = function () {
    sendReaction(idAvis, "down"); // Nouvelle action
  };

  thumbUp.onclick = function () {
    sendReaction(idAvis, "up"); // Nouvelle action
  };

  // Gestion de la réaction "down"
  if (action === "down" || action === "upTOdown") {
    thumbDown.classList.remove("fa-regular");
    thumbDown.classList.add("fa-solid", "text-rouge-logo");

    // Incrémentation du compteur de dislikes
    const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
    dislikeCountElement.textContent = currentDislikes + 1;

    // Décrémentation du compteur de likes si l'utilisateur change de réaction
    if (action === "upTOdown") {
      const currentLikes = parseInt(likeCountElement.textContent) || 0;
      likeCountElement.textContent = currentLikes - 1;
    }

    // Mise à jour des événements onclick
    thumbDown.onclick = function () {
      sendReaction(idAvis, "downTOnull"); // Nouvelle action pour annuler
    };

    thumbUp.onclick = function () {
      sendReaction(idAvis, "downTOup"); // Nouvelle action
    };
  }

  // Gestion de la réaction "up"
  if (action === "up" || action === "downTOup") {
    thumbUp.classList.remove("fa-regular");
    thumbUp.classList.add("fa-solid", "text-secondary");

    // Incrémentation du compteur de likes
    const currentLikes = parseInt(likeCountElement.textContent) || 0;
    likeCountElement.textContent = currentLikes + 1;

    // Décrémentation du compteur de dislikes si l'utilisateur change de réaction
    if (action === "downTOup") {
      const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
      dislikeCountElement.textContent = currentDislikes - 1;
    }

    // Mise à jour des événements onclick
    thumbUp.onclick = function () {
      sendReaction(idAvis, "upTOnull"); // Nouvelle action pour annuler
    };

    thumbDown.onclick = function () {
      sendReaction(idAvis, "upTOdown"); // Nouvelle action
    };
  }

  if (action === "upTOnull") {
    const currentLikes = parseInt(likeCountElement.textContent) || 0;
    likeCountElement.textContent = currentLikes - 1;
  }

  if (action === "downTOnull") {
    const currentDislikes = parseInt(dislikeCountElement.textContent) || 0;
    dislikeCountElement.textContent = currentDislikes - 1;
  }

  // Envoi de la requête pour mettre à jour la réaction
  const url = `/scripts/thumb.php?id_avis=${idAvis}&action=${action}`;

  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur réseau");
      }
      return response.json();
    })
    .then((data) => {
      const resultDiv = document.getElementById(`reaction-result-${idAvis}`);
      if (data.success) {
        resultDiv.innerHTML = `Réaction mise à jour : ${data.message}`;
      } else {
        resultDiv.innerHTML = `Erreur : ${data.message}`;
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la requête:", error);
    });
}
window.sendReaction = sendReaction;

// Date expérience antérieure au jour actuel (dans les pages où il y en a)
function setMaxDate() {
  const select_date = document.getElementById("date_experience");
  if (select_date) {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");
    const maxDate = `${year}-${month}-${day}`;

    select_date.setAttribute("max", maxDate);
  }
}
window.onload = setMaxDate;
