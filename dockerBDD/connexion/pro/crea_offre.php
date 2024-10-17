<?php

$options = [
    'stjxd' => 'Gratuite',
    'Standard' => 'Standard',
    'Premium' => 'Premium',
];

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="crea_offre.js"></script>
    <title>Création d'offre</title>
    <style>
        .offer-form {
            display: none;
            margin-top: 10px;
        }

        .offer-tag{
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<form onsubmit="event.preventDefault();">
    <?php foreach ($options as $value => $label): ?>
        <div>
            <label>
                <input type="radio" name="options" value="<?php echo $value; ?>">
                <?php echo $label; ?>
            </label>
        </div>

        <div class="offer-form" id="<?php echo $value; ?>">
            <h3>Création de l'offre <?php echo $label; ?></h3>
            <label for="<?php echo $value; ?>_name">Nom de l'offre:</label>
            <select name="tag" id=tag>
                <option value="selection">Type* </option>
                <?php 
                foreach ($tag as $value){ ?>
                    <option value="tag" name="test"><?php echo $value ?></option>
                <?php }; ?>
            </select><br>
        </div>
    <?php endforeach; ?>

    <div class="offer-tag" id="tag1">

    <h1>dehiohjogh</h1>

    </div>

    <button onclick="showForm();">Valider</button>
</form>

</body>
</html>