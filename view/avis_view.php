
console.log("La partie 1 est valide");<!-- 
    POUR APPELER LA VUE AVIS, DÉFINIR LES VARIABLES SUIVANTES EN AMONT :
    - $id_avis
    - $date_publication
    - $date_experience
    - $commentaire
    - $id_avis_reponse
    - $id_membre
    - $titre
-->

<?php

// Import d'outils (controllers)
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
$membreController = new MembreController();
$proPublicController = new ProPublicController();
$proPriveController = new ProPriveController();
$avisController = new avisController();

?>

<!-- CARTE DE L'AVIS COMPORTANT TOUTES LES INFORMATIONS NÉCESSAIRES (PRO / MEMBRE) -->
<div class="avis w-full rounded-lg border border-black">
    <?php
    // Obtenir la variables regroupant les infos du membre
    $membre = $membreController->getInfosMembre($id_membre);
    $avis = $avisController->getAvisById($id_avis);
    ?>

    <!-- Prénom, nom -->
    <p><?php echo $membre['prenom'] . ' ' . $membre['nom'] ?></p>

    <!-- Note sur 5 -->
    <div class="flex gap-1">
        <?php
        // Note s'il y en a une
        $note = floatval($avis['note']);
        $note = floatval('3.5');

        for ($i = 0; $i < 5; $i++) {
            if ($note > 1) {
                ?>
                <img src="/public/images/oeuf_plein.svg" alt="1 point de note">
                <?php
            } else if ($note > 0) {
                ?>
                    <img src="/public/images/oeuf_moitie.svg" alt="1 point de note">
                <?php
            } else {
                ?>
                    <img src="/public/images/oeuf_vide.svg" alt="1 point de note">
                <?php
            }
            $note--;
        }
        ?>
    </div>

</div>