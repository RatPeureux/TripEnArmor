
console.log("La partie 1 est valide");<!-- 
    POUR APPELER LA VUE AVIS, DÉFINIR LES VARIABLES SUIVANTES EN AMONT :
    - $id_avis
    - $id_membre
-->

<?php
// Import des controllers
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
$membreController = new MembreController();
$proPublicController = new ProPublicController();
$proPriveController = new ProPriveController();
$avisController = new avisController();
?>

<!-- CARTE DE L'AVIS COMPORTANT TOUTES LES INFORMATIONS NÉCESSAIRES (MEMBRE) -->
<div class="avis w-full rounded-lg border border-black p-2">
    <?php
    // Obtenir la variables regroupant les infos du membre
    $membre = $membreController->getInfosMembre($id_membre);
    $avis = $avisController->getAvisById($id_avis);
    ?>

    <!-- Première ligne du haut -->
    <div class="flex gap-3 items-center">
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
                    <img class="w-3" src="/public/images/oeuf_plein.svg" alt="1 point de note">
                    <?php
                } else if ($note > 0) {
                    ?>
                        <img class="w-3" src="/public/images/oeuf_moitie.svg" alt="1 point de note">
                    <?php
                } else {
                    ?>
                        <img class="w-3" src="/public/images/oeuf_vide.svg" alt="1 point de note">
                    <?php
                }
                $note--;
            }
            ?>
        </div>

        <!-- Date de publication -->
        <?php
        if ($avis['date_publication']) { ?>
            <p class="italic grow">Posté le <?php echo $avis['date_publication'] ?></p>
            <?php
        }
        ?>

        <!-- Drapeau de signalement -->
        <a href="#" onclick="confirm('Signaler l\'avis ?')"><i class="fa-regular text-h2 fa-flag"></i></a>
    </div>

    <!-- Date d'expérience + contexte de passage -->
    <?php
    if ($avis['date_experience']) { ?>
        <div class="flex justify-start gap-3">
            <p class="italic">Vécu le
                <?php echo $avis['date_experience'];
                echo (isset($avis['contexte'])) ? $avis['contexte'] : '' ?>
            </p>
        </div>
        <?php
    }
    ?>

    <?php
    // Titre de l'avis s'il y en a un
    if ($avis['titre']) { ?>
        <p class="text-h4 font-bold"><?php echo $avis['titre'] ?></p>
    <?php }
    ?>

    <?php
    // Commentaire de l'avis s'il y en a un
    if ($avis['commentaire']) { ?>
        <p><?php echo $avis['commentaire'] ?></p>
    <?php }
    ?>
</div>