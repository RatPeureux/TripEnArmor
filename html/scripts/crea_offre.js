function showFormPart1() {
    const selectedValider = document.getElementById('part-1');
    console.log(selectedValider); // Affiche l'option sélectionnée dans la console

    // Récupère tous les formulaires avec la classe "offer-form"
    const forms = document.querySelectorAll('.offer-form-part-1');

    // Masque tous les formulaires
    forms.forEach(form => form.style.display = 'none');

    // Si une option est sélectionnée, affiche le formulaire correspondant
    if (selectedValider) {
        const selectedForm = document.getElementById('part-1'); // Récupère le formulaire correspondant à la valeur de l'option sélectionnée
        if (selectedForm) {
            document.getElementById('part-1').style.display = 'block'; // Affiche le formulaire
        }
    }
}

function showFormPart2() {
    console.log("part 2");
    
    const selectedDerniereEtape = document.getElementById('part-2');
    console.log(selectedDerniereEtape);

    const forms = document.querySelectorAll('.offer-form-part-2');

    forms.forEach(form => form.style.display = 'none');

    if (selectedDerniereEtape) {
        console.log("réussi");
        const selectedForm = document.getElementById('part-2');
        if (selectedForm) {
            console.log("Le mec est juste monstrueux");
            document.getElementById('part-2').style.display = 'block';
        }
    }
}

function gratuit(){

    const selectedGratuit = document.getElementById('op');

    const forms = document.querySelectorAll('.avecOptions');

    // forms.forEach(form => form.style.display = 'block');

    if (selectedGratuit) {
        console.log("réussi");
        const selectedForm = document.getElementById('op');
        if (selectedForm) {
            console.log("Le mec est juste monstrueux");
            document.getElementById('op').style.display = 'none';
        }else{
            document.getElementById('op').style.display = 'block';
        }
    }

}

function standard(){

    const selectedGratuit = document.getElementById('op');

    const forms = document.querySelectorAll('.avecOptions');

    // forms.forEach(form => form.style.display = 'block');

    if (selectedGratuit) {
        console.log("réussi");
        const selectedForm = document.getElementById('op');
        if (selectedForm) {
            console.log("Le mec est juste monstrueux");
            document.getElementById('op').style.display = 'block';
        }
    }

}

function premium(){

    const selectedGratuit = document.getElementById('op');

    const forms = document.querySelectorAll('.avecOptions');

    // forms.forEach(form => form.style.display = 'block');

    if (selectedGratuit) {
        console.log("réussi");
        const selectedForm = document.getElementById('op');
        if (selectedForm) {
            console.log("Le mec est juste monstrueux");
            document.getElementById('op').style.display = 'block';
        }
    }

}

window.showFormPart1 = showFormPart1;
window.showFormPart2 = showFormPart2;
window.gratuit = gratuit;
window.standard = standard;
window.premium = premium;
