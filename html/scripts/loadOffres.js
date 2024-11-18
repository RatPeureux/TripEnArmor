async function loadOffres() {
    try {
        const response = await fetch('../php/pro/affiche-offre.php');
        const offres = await response.json();

        console.log('Données reçues:', offres); // Log des données reçues

        if (Array.isArray(offres) && offres.length > 0) {
            const container = document.getElementById('offres-container');
            container.innerHTML = ''; // Clear any previous content

            offres.forEach(offre => {
                console.log('Offre actuelle:', offre); // Log de l'offre actuelle

                // Vérifiez que l'ID de l'offre est défini
                if (offre.offre_id === undefined) {
                    console.error("L'ID de l'offre est undefined pour l'offre :", offre);
                    return; // Ne pas créer de carte pour cette offre
                }

                const card = document.createElement('div');
                card.className = 'card active relative bg-base300 rounded-lg flex h-[400px]';

                console.log('ID de l\'offre:', offre.offre_id);
                const offreId = offre.offre_id;


                // Construire le HTML de l'offre
                card.innerHTML = `
                    <div class="gauche relative shrink-0 basis-1/2 overflow-hidden" onclick="location.href='details.php'">
                        <div class="en-tete flex justify-around absolute top-0 w-full">
                            <div class="bg-bgBlur/75 backdrop-blur rounded-b-lg w-3/5">
                                <h3 class="text-center font-bold">${offre.resume_offre}</h3>
                                <div class="flex w-full justify-between px-2">
                                    <p class="text-small">${offre.adresse_postale}</p>
                                    <p class="text-small">Restauration</p>
                                </div>
                            </div>

                            <!-- Formulaire pour changer l'état de l'offre -->
                        <form method="POST" action="../dockerBDD/connexion/pro/connected_pro.php" onsubmit="return confirm('Voulez-vous changer l\'état de l\'offre ?');">
                            <input type="hidden" name="idoffre" value="${offre.offre_id}">
                            <input type="hidden" name="est_en_ligne" value="${offre.est_en_ligne}">

                            <button type="submit" name="changer_etat" class="bg-bgBlur/75 absolute right-4 backdrop-blur flex justify-center items-center p-1 rounded-b-lg">
                                <i class="${offre.est_en_ligne ? 'fa-solid fa-wifi' : 'fa-solid fa-wifi-slash'}"></i>
                                <span class="sr-only">${offre.est_en_ligne ? 'Mettre hors ligne' : 'Mettre en ligne'}</span>
                            </button>
                        </form>

                        </div>
                        <img class="rounded-l-lg w-full h-full object-cover object-center" src="../public/images/image-test.png" alt="Image promotionnelle de l'offre">
                    </div>
                    <div class="infos flex flex-col items-center self-stretch px-5 py-3 gap-3 justify-between" onclick="location.href='details.php'">
                        <div class="description py-2 flex flex-col gap-2 h-full">
                            <div class="p-2 rounded-lg bg-secondary self-center">
                                <p class="text-white text-center font-bold">Petit déjeuner, Dîner, Boissons</p>
                            </div>
                            <p class="line-clamp-6">${offre.description_offre}</p>
                        </div>
                        <div class="self-stretch flex flex-col shrink-0 gap-2">
                            <hr class="border-black w-full">
                            <div class="flex justify-around self-stretch">
                                <div class="localisation flex flex-col gap-2 flex-shrink-0 justify-center items-center">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <p class="text-small">${offre.ville}</p>
                                    <p class="text-small">${offre.code_postal}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Ajouter la carte au container
                container.appendChild(card);
            });
        } else {
            document.getElementById('offres-container').innerHTML = '<p>Aucune offre disponible.</p>';
        }
    } catch (error) {
        console.error('Erreur lors du chargement des offres:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadOffres();
});