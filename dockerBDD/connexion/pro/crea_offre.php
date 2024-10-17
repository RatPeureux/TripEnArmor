<?php
// Définit un tableau d'options pour le type d'offre
$options = [
    'stjxd' => 'Gratuite',
    'Standard' => 'Standard',
    'Premium' => 'Premium',
];

// Définit un tableau de tags pour classifier les offres
$tag = [
    'Tag1' => 'Activite',
    'Tag2' => 'Visite',
    'Tag3' => 'Spectacle',
    'Tag4' => 'Parc d attraction',
    'Tag5' => 'Restauration'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <script type="module" src="crea_offre.js"></script> <!-- Inclut le fichier JavaScript -->
    <title>Création d'offre</title> <!-- Titre de la page -->
    <style>
        .offer-form { /* Style pour masquer les formulaires d'offre par défaut */
            display: none;
            margin-top: 10px; /* Espace au-dessus des formulaires */
        }

        .offer-tag { /* Style pour masquer les tags par défaut */
            display: none;
            margin-top: 10px; /* Espace au-dessus des tags */
        }
    </style>
</head>
<body>

<form onsubmit="event.preventDefault();"> <!-- Empêche la soumission par défaut du formulaire -->
    <?php foreach ($options as $value => $label): ?> <!-- Boucle à travers les options -->
        <div>
            <label>
                <input type="radio" name="options" value="<?php echo $value; ?>"> <!-- Boutons radio pour sélectionner le type d'offre -->
                <?php echo $label; ?> <!-- Affiche le label de l'option -->
            </label>
        </div>

        <div class="offer-form" id="<?php echo $value; ?>"> <!-- Formulaire correspondant à chaque option -->
            <h3>Création de l'offre <?php echo $label; ?></h3> <!-- Titre pour le formulaire -->
            <label for="<?php echo $value; ?>_name">Nom de l'offre:</label> <!-- Label pour le champ de saisie -->
            <select name="tag" id="tag"> <!-- Sélecteur pour choisir un tag -->
                <option value="selection">Type*</option> <!-- Option par défaut -->
                <?php 
                foreach ($tag as $value){ ?>
                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option> <!-- Options de tag générées dynamiquement -->
                <?php }; ?>
            </select><br> <!-- Fin du sélecteur -->
        </div>
    <?php endforeach; ?> <!-- Fin de la boucle foreach -->

    <div class="offer-tag" id="tag1"> <!-- Section pour les tags, masquée par défaut -->
        <h1>dehiohjogh</h1> <!-- Contenu à afficher lorsque le tag est sélectionné -->
    </div>

    <button onclick="showForm();">Valider</button> <!-- Bouton pour valider, appelle la fonction showForm() -->
</form>

</body>
</html>
