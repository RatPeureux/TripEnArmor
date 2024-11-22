<?php

require_once __DIR__ . "/../../controller/activite_controller.php";

for ($id_activite = 0; $id_activite < 10; $id_activite++) {
    require __DIR__ . "/../../view/activite_carte.php";
}

require __DIR__ . "/../../view/bouton.php";
?>