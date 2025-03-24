<div class="z-30 p-5 bg-white border border-black flex flex-col gap-5 max-w-[60%]">

    <!-- Information sur vos tickets concernant -->
    <h2 class="text-2xl text-center">Attention</h2>

    <form action="/scripts/supprimer_compte_membre.php" class="flex flex-col items-start gap-4" method="POST">
        <label class="text-justify" for="textConfirmDelete">
            Vous êtes sur le point du supprimer votre compte entièrement.
            <br><br>
            Etes-vous ŝur(e) de vouloir supprimer votre compte ? L'action est irréversible.
            Toutes données personnelles et relatives à votre compte seront détruites et
            vos avis anonymisés.

            Si vous voulez vraiment supprimer ce compte, écrivez :
            <br>
            <span class="font-bold">"je veux supprimer mon compte"</span>
        </label>
        <input class="border border-secondary p-1" type="text" name="textConfirmDelete" id="textConfirmDelete">

        <input disabled id="submitDelete" type="submit" value="Supprimer mon compte"
            class="py-2 px-4 bg-primary rounded-full text-white cursor-pointer opacity-50">
    </form>

    <script>
        // Sélectionner les éléments
        const textConfirmDelete = document.getElementById('textConfirmDelete');
        const submitBtn = document.getElementById('submitDelete');

        // Fonction qui vérifie si le texte correspond à la phrase exacte
        function checkConfirmation() {
            if (textConfirmDelete.value == "je veux supprimer mon compte") {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
            }
        }

        // Ajouter un événement pour vérifier la valeur du champ à chaque saisie
        textConfirmDelete.addEventListener('input', checkConfirmation);
    </script>
</div>