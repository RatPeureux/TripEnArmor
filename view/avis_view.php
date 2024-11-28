<!-- 
    POUR APPELER LA VUE AVIS, DÉFINIR LES VARIABLES SUIVANTES EN AMONT :
    - $id_avis
    - $date_publication
    - $date_experience
    - $commentaire
    - $id_avis_reponse
    - $id_compte
    - $titre
-->

<?php

// Import d'outils (controllers)
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
$membreController = new MembreController();
$proPublicController = new ProPublicController();
$proPriveController = new ProPriveController();

?>

<!-- CARTE DE L'AVIS COMPORTANT TOUTES LES INFORMATIONS NÉCESSAIRES (PRO / MEMBRE) -->
<div class="avis">
    <?php
    $result = $proPublicController->getInfosProPublic($id_compte);
    ?>
</div>