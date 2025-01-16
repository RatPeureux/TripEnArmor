<form id="formulaire-signalement-<?php echo $id_avis ?>" class="z-30 p-5 bg-white border border-black flex flex-col">
    <!-- MOTIF -->
    <label for="motif">Motif :</label>
    <select required name="motif" id="motif" class="mb-5" required>
        <option value="" selected disabled>-- Motif --</option>
        <option value="desinformation">Désinformation</option>
        <option value="discrimination">Discrimination</option>
        <option value="illegal">Illégalité</option>
        <option value="irrespect">Irrespect</option>
        <option value="autre">Autre</option>
    </select>

    <!-- COMMENTAIRE (optionnel) -->
    <label for="commentaire-signalement">Commentaire</label>
    <textarea name="commentaire-signalement" id="commentaire-signalement" class="mb-5 w-[400px] h-[150px]"></textarea>

    <!-- Bouton de signalement -->
    <input type="submit" class="hover:cursor-pointer self-end max-w-sm h-12 px-4 text-small text-white bg-primary" value="Signaler"></input>
</form>

<script>
    document.getElementById("formulaire-signalement-<?php echo $id_avis ?>").addEventListener("submit", function (event) {
        event.preventDefault();

        const motif = document.getElementById("motif").value;
        const commentaireSignalement = document.getElementById("commentaire-signalement").value;
        const dateTime = new Date().toLocaleString();

        const url = `/scripts/signaler.php?motif=${encodeURIComponent(motif)}&commentaireSignalement=${encodeURIComponent(commentaireSignalement)}&dateTime=${encodeURIComponent(dateTime)}&id_avis=<?php echo $id_avis ?>`;

        fetch(url, {
            method: "GET",
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Affiche l'alerte avec le message
                document.getElementById('pop-up-signalement-<?php echo $id_avis ?>').classList.add('hidden'); // Ajoute un log après l'alerte
            })
            .catch(error => {
                alert("Erreur : " + error);
            });
    });
</script>
