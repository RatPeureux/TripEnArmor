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
    <title>Création d'offre</title>
    <style>
        .offer-form {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function showForm() {
            
            const selectedOption = document.querySelector('input[name="options"]:checked');
            const forms = document.querySelectorAll('.offer-form');

            
            forms.forEach(form => form.style.display = 'none');

            
            if (selectedOption) {
                const selectedForm = document.getElementById(selectedOption.value);
                if (selectedForm) {
                    selectedForm.style.display = 'block';
                }
            }
        }
    </script>
</head>
<body>

<form onsubmit="event.preventDefault(); showForm();">
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
            <select name="<?php echo $value; ?>_name" id="<?php echo $value; ?>_name">
                <option value="selection">Type* </option>
                <?php 
                foreach ($tag as $value){ ?>
                    <option value="tag"><?php echo $value ?></option>
                <?php }; ?>
            </select><br>
        </div>
    <?php endforeach; ?>

    

    <input type="submit" value="Valider">
</form>

</body>
</html>