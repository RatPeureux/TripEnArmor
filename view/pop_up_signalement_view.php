<form id="formulaire-signalement-<?php echo $id_avis ?>" class="z-30 p-5 bg-white border border-black flex flex-col">

    <h2 class="text-h2 text-center">Signaler un avis</h2>

    <!-- MOTIF -->
    <label for="motif-<?php echo $id_avis ?>" class="text-h4">Motif</label>
    <select required name="motif-<?php echo $id_avis ?>" id="motif-<?php echo $id_avis ?>"
        class="mb-5 border border-black text-h4 bg-transparent text-center">
        <option value="" selected disabled>-- Motif --</option>
        <option value="desinformation">Désinformation</option>
        <option value="discrimination">Discrimination</option>
        <option value="illegal">Illégalité</option>
        <option value="irrespect">Irrespect</option>
        <option value="autre">Autre</option>
    </select>

    <!-- COMMENTAIRE (optionnel) -->
    <label for="commentaire-signalement-<?php echo $id_avis ?>" class="text-h4">Commentaire</label>
    <textarea name="commentaire-signalement-<?php echo $id_avis ?>" id="commentaire-signalement-<?php echo $id_avis ?>"
        class="mb-5 w-[400px] h-[150px] border border-black"></textarea>

    <!-- Bouton de signalement -->
    <input type="submit" class="hover:cursor-pointer self-end max-w-sm h-12 px-4 text-small text-white bg-primary"
        value="Signaler">
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
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                } else if (data.error) {
                    alert(data.error)
                }
                document.getElementById('pop-up-signalement-<?php echo $id_avis ?>').classList.add('hidden'); // Ajoute un log après l'alerte
            })
            .catch(error => {
                alert("Erreur : " + error);
            });
    });
</script>