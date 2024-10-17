<?php
include('../connect_params.php');
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

    

    try {

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gère les erreurs de PDO
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['titre']) && !empty($_POST['titre']) && isset($_POST['description']) && !empty($_POST['description'])) {
                $titre = $_POST['titre'];
                $description = $_POST['description'];
        
                // Préparer la requête d'insertion
                $stmt = $dbh->prepare("INSERT INTO sae._offre (titre, description, enLigne, idAdresse, idOrganisation, a_la_une, en_relief) VALUES (:titre, :description, true, 1, 1, false, false)");
        
                // Lier les paramètres
                $stmt->bindParam(':titre', $titre);
                $stmt->bindParam(':description', $description);
        
                // Exécuter la requête
                if ($stmt->execute()) {
                    echo "Offre créée avec succès!";
                } else {
                    echo "Erreur lors de la création de l'offre.";
                }
            } else {
                echo "Veuillez remplir le champ Titre.";
            }
        }
    } catch (\Throwable $e) {
        // Affiche une erreur en cas d'échec de la connexion à la base de données
        echo "Erreur !: " . $e->getMessage();
        die(); // Termine le script
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
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

        .offer-desc {
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<form action="" onsubmit="event.preventDefault();" method="post"> <!-- Empêche la soumission par défaut du formulaire -->
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

    
    <button onclick="showForm()">Valider</button>
    
    
</form>

<form action="" method="POST">
<div class="offer-tag" id="tag1"> <!-- Section pour les tags, masquée par défaut -->
        
        <!-- Contenu à afficher lorsque le tag est sélectionné -->
        <label for="titre">Titre*: </label>
        <input type="text" name="titre" id="titre"><br>
        <label for="tag-form">TAG*:</label>
        <input type="text" name="tag-form" id="tag-form"><br>
        <label for="auteur">Auteur*:</label>
        <input type="text" name="auteur" id="auteur"><br>
        <label for="ville">Ville*:</label>
        <input type="text" name="ville" id="ville">
        <label for="code-postal">Code postal*:</label>
        <input type="text" name="code-postal" id="code-postal"><br>
        <label for="adresse">Adresse*:</label>
        <input type="text" name="adresse" id="adresse"><br>
        <label for="description">Description*:</label>
        <input type="text" name="description" id="description">
        <input type="submit" value="envoyer">
    </div>
</form>
<button onclick="showTag()">TAG</button> <!-- Bouton pour valider, appelle la fonction showForm()  -->

<form action="" method="post">

    <div class="offer-desc" id="desc">

        

    </div>
        
</form>

<script type="module" src="crea_offre.js"></script> <!-- Inclut le fichier JavaScript -->
</body>
</html>
