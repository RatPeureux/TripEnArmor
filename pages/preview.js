// Fonction pour mettre à jour la preview en temps réel
function updatePreview() {
    const titre = document.getElementById('titre').value;
    const tag = document.getElementById('tag').value;
    const auteur = document.getElementById('auteur').value;
    const ville = document.getElementById('ville').value;
    const code = document.getElementById('code').value;
    const prix = document.getElementById('prix').value;
    const resume = document.getElementById('resume').value;

    console.log("Titre:", titre);
    console.log("Tag:", tag);
    console.log("Auteur:", auteur);
    console.log("Ville:", ville);
    console.log("Code postal:", code);
    console.log("Prix minimal:", prix);
    console.log("Résumé:", resume);

    // Mise à jour de la section preview
    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-tag').textContent = `Tag : ${tag}`;
    document.getElementById('preview-auteur').textContent = `Auteur : ${auteur}`;
    document.getElementById('preview-ville').textContent = `Ville : ${ville}`;
    document.getElementById('preview-code').textContent = `Code postal : ${code}`;
    document.getElementById('preview-prix').textContent = `Prix minimal : ${prix} €`;
    document.getElementById('preview-resume').textContent = resume;
}

// Fonction pour mettre à jour l'image preview
document.getElementById('photo-upload-carte').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImage = document.getElementById('preview-image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.src = '#'; // Image par défaut ou vide si aucun fichier
    }
});

// Ajout des événements sur les champs du formulaire
document.getElementById('titre').addEventListener('input', updatePreview);
document.getElementById('tag').addEventListener('input', updatePreview);
document.getElementById('auteur').addEventListener('input', updatePreview);
document.getElementById('ville').addEventListener('input', updatePreview);
document.getElementById('code').addEventListener('input', updatePreview);
document.getElementById('prix').addEventListener('input', updatePreview);
document.getElementById('resume').addEventListener('input', updatePreview);


document.getElementById('titre').addEventListener('input', function() {
    document.getElementById('preview-titre').textContent = document.getElementById('titre').value;
});

document.getElementById('tag').addEventListener('input', function() {
    document.getElementById('preview-tag').textContent = 'Tag : ' + document.getElementById('tag').value;
});