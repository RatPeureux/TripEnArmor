function showForm() {
    console.log("test"); // Affiche "test" dans la console pour vérifier que la fonction est appelée

    // Récupère l'option sélectionnée dans les boutons radio
    const selectedOption = document.querySelector('input[name="options"]:checked');
    console.log(selectedOption); // Affiche l'option sélectionnée dans la console

    // Récupère tous les formulaires avec la classe "offer-form"
    const forms = document.querySelectorAll('.offer-form');

    // Masque tous les formulaires
    forms.forEach(form => form.style.display = 'none');

    // Si une option est sélectionnée, affiche le formulaire correspondant
    if (selectedOption) {
        const selectedForm = document.getElementById(selectedOption.value); // Récupère le formulaire correspondant à la valeur de l'option sélectionnée
        if (selectedForm) {
            selectedForm.style.display = 'block'; // Affiche le formulaire
        }
    }
}

function showTag() {
    // Récupère l'élément sélectionné dans le sélecteur d'options
    const selectedTag = document.querySelector('#tag');
    // Récupère tous les éléments avec la classe "offer-tag"
    const afterTag = document.querySelectorAll('.offer-tag');

    // Si une option est sélectionnée dans le sélecteur, affiche l'élément correspondant
    if (selectedTag) {
        const selectedFormTag = selectedTag.value; // Récupère la valeur de l'option sélectionnée
        if (selectedFormTag) {
            document.getElementById('tag1').style.display = 'block'; // Affiche l'élément correspondant
        }
    }
}
