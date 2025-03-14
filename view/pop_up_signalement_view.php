<form id="formulaire-signalement-<?php echo $id_avis ?>" class="z-30 p-5 bg-white border border-black flex flex-col">

    <h2 class="text-2xl text-center">Signaler un avis</h2>

    <!-- MOTIF -->
    <label for="motif-<?php echo $id_avis ?>" class="text-lg">Motif</label>
    <select required name="motif-<?php echo $id_avis ?>" id="motif-<?php echo $id_avis ?>"
        class="mb-5 border border-black text-lg bg-transparent text-center"
       >
        <option value="" selected disabled>-- Motif --</option>
        <option value="desinformation">Désinformation</option>
        <option value="discrimination">Discrimination</option>
        <option value="illegal">Illégalité</option>
        <option value="irrespect">Irrespect</option>
        <option value="autre">Autre</option>
    </select>

    <!-- COMMENTAIRE (optionnel) -->
    <label for="commentaire-signalement-<?php echo $id_avis ?>" class="text-lg">Commentaire</label>
    <textarea name="commentaire-signalement-<?php echo $id_avis ?>" id="commentaire-signalement-<?php echo $id_avis ?>"
       
        class="mb-5 w-[400px] h-[150px] border border-black"></textarea>

    <!-- Bouton de signalement -->
    <input type="submit" class="hover:cursor-pointer self-end max-w-sm h-12 px-4 text-sm text-white bg-primary"
        value="Signaler"></input>
</form>

<script>
    document.getElementById("formulaire-signalement-<?php echo $id_avis ?>").addEventListener("submit", function (event) {
    event.preventDefault();
    
    const motif = document.getElementById("motif-<?php echo $id_avis ?>").value;
    const commentaireSignalement = document.getElementById("commentaire-signalement-<?php echo $id_avis ?>").value;
    const dateTime = new Date().toLocaleString();
    
    const url = `/scripts/signaler.php?motif=${encodeURIComponent(motif)}&commentaireSignalement=${encodeURIComponent(commentaireSignalement)}&dateTime=${encodeURIComponent(dateTime)}&id_avis=<?php echo $id_avis ?>`;
    
    fetch(url, {
        method: "GET",
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        // Fermer le popup
        document.getElementById('pop-up-signalement-<?php echo $id_avis ?>').classList.add('hidden');
        
        // Afficher le toast de confirmation
        if (data.success || data.message) {
            // Créer et afficher un toast noir avec texte blanc
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 bg-black text-white p-4 rounded shadow-lg z-50';
            toast.textContent = data.message || "Avis signalé avec succès";
            toast.style.opacity = '1';
            toast.style.transition = 'opacity 0.5s';
            document.body.appendChild(toast);
            
            // Faire disparaître le toast après 3 secondes
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error("Erreur :", error);
        alert("Une erreur est survenue lors du signalement. Veuillez réessayer.");
    });
});
</script>