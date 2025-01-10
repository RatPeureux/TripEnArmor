<!-- 
    POUR APPELER LA VUE MON_AVIS, DÉFINIR LES VARIABLES SUIVANTES EN AMONT :
    - $id_avisavis_view
    - $id_membre
    - $mode         : soit 'avis', soit 'mon_avis' pour un affichage différent
-->

<?php
if ($mode == 'avis') {
    $is_mon_avis = false;
} else if ($mode == 'mon_avis') {
    $is_mon_avis = true;
}

// Import des controllers
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/restauration_controller.php';
$membreController = new MembreController();
$proPublicController = new ProPublicController();
$proPriveController = new ProPriveController();
$avisController = new avisController();
$restaurationController = new RestaurationController();

if (!function_exists('to_nom_note')) {
    function to_nom_note($nom_attribut_note): string
    {
        return str_replace('_', ' ', explode('_', $nom_attribut_note, 2)[1]);
    }
}
?>

<!-- CARTE DE L'AVIS COMPORTANT TOUTES LES INFORMATIONS NÉCESSAIRES (MEMBRE) -->
<div
    class="avis w-full rounded-lg border border-black <?php echo $is_mon_avis ? 'border-primary border-4' : '' ?> p-2 flex flex-col gap-1">
    <?php
    // Obtenir la variables regroupant les infos du membre
    $membre = $membreController->getInfosMembre($id_membre);
    $avis = $avisController->getAvisById($id_avis);
    $restauration = $restaurationController->getInfosRestauration($avis['id_offre']);
    ?>

    <!-- Première ligne du haut -->
    <div class="flex gap-3 items-center">
        <!-- Prénom, nom -->
        <p><?php echo $membre['pseudo'] ?></p>

        <!-- Note sur 5 -->
        <div class="flex gap-1 grow shrink-0">
            <?php
            // Note s'il y en a une
            $note = floatval($avis['note']);
            for ($i = 0; $i < 5; $i++) {
                if ($note >= 1) {
                    ?>
                    <img class="w-3" src="/public/icones/oeuf_plein.svg" alt="1 point de note">
                    <?php
                } else if ($note > 0) {
                    ?>
                        <img class="w-3" src="/public/icones/oeuf_moitie.svg" alt="0.5 point de note">
                    <?php
                } else {
                    ?>
                        <img class="w-3" src="/public/icones/oeuf_vide.svg" alt="0 point de note">
                    <?php
                }
                $note--;
            }
            ?>
        </div>

        <?php
        if (!$is_mon_avis) {
            ?>
            <!-- Drapeau de signalement -->
            <a onclick="confirm('Signaler l\'avis ?')">
                <i class="fa-solid fa-flag text-h2"></i>
            </a>
            <?php
        } else {
            ?>
            <!-- Poubelle de suppression d'avis -->
            <a href="/scripts/delete_avis.php?id_avis=<?php echo $id_avis ?>&id_offre=<?php echo $avis['id_offre'] ?>"
                onclick="return confirm('Supprimer votre avis ?')">
                <i class="fa-solid fa-trash text-h2"></i>
            </a>
            <?php
        }
        ?>

    </div>

    <!-- Date de publication (2ème ligne) -->
    <?php
    if ($avis['date_publication']) { ?>
        <p class="italic grow"><?php echo date('d/m/Y', strtotime($avis['date_publication'])) ?></p>
        <?php
    }
    ?>

    <!-- Notes complémentaires d'un restaurant) -->
    <?php
    // Notes pour les restaurants
    if ($restauration) { ?>
        <div class='flex justify-around flex-wrap'>
            <?php require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
            $stmt = $dbh->prepare("SELECT * FROM sae_db._avis_restauration_note WHERE id_avis = :id_avis AND id_restauration = :id_restauration");
            $stmt->bindParam(":id_avis", $id_avis);
            $stmt->bindParam(":id_restauration", $restauration['id_offre']);
            $stmt->execute();
            $notes_restauration = $stmt->fetch();

            foreach (['note_ambiance', 'note_service', 'note_cuisine', 'rapport_qualite_prix'] as $nom_note) {
                ?>

                <div class='flex flex-col items-center shrink-0'>
                    <div class="flex gap-1">
                        <?php
                        $note = isset($notes_restauration[$nom_note]) ? floatval($notes_restauration[$nom_note]) : 2.5;
                        for ($i = 0; $i < 5; $i++) {
                            if ($note >= 1) {
                                ?>
                                <img class="w-3" src="/public/icones/oeuf_plein.svg" alt="1 point de note">
                                <?php
                            } else if ($note > 0) {
                                ?>
                                    <img class="w-3" src="/public/icones/oeuf_moitie.svg" alt="0.5 point de note">
                                <?php
                            } else {
                                ?>
                                    <img class="w-3" src="/public/icones/oeuf_vide.svg" alt="0 point de note">
                                <?php
                            }
                            $note--;
                        }
                        ?>
                    </div>

                    <p class='italic'><?php print_r(to_nom_note($nom_note)) ?></p>
                </div>

                <?php
            } ?>
        </div>
        <?php
    }
    ?>

    <!-- Date d'expérience + contexte de passage -->
    <?php
    if ($avis['date_experience']) { ?>
        <div class="flex justify-start gap-3">
            <p class="italic">Vécu le
                <?php echo $avis['date_experience'] ?>,
                <?php echo (isset($avis['contexte_passage'])) ? $avis['contexte_passage'] : '' ?>
            </p>
        </div>
        <?php
    }
    ?>

    <?php
    // Titre de l'avis s'il y en a un
    if ($avis['titre']) { ?>
        <p class="text-h4 font-bold mt-2"><?php echo $avis['titre'] ?></p>
    <?php }
    ?>

    <?php
    // Commentaire de l'avis s'il y en a un
    if ($avis['commentaire']) { ?>
        <p><?php echo $avis['commentaire'] ?></p>
    <?php }
    ?>
</div>