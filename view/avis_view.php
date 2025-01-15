<!-- 
    POUR APPELER LA VUE AVIS, DÉFINIR LES VARIABLES SUIVANTES EN AMONT :
    - $id_avis
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
<div class="avis w-full   <?php echo $is_mon_avis ? 'border-primary border-4' : '' ?> p-2 flex flex-col gap-1">
    <?php
    // Obtenir la variables regroupant les infos du membre
    $membre = $membreController->getInfosMembre($id_membre);
    $avis = $avisController->getAvisById($id_avis);
    $restauration = $restaurationController->getInfosRestauration($avis['id_offre']);
    ?>

    <!-- Première ligne du haut -->
    <div class="flex gap-3 items-center text-small">
        <div class="flex">
            <!-- Prénom, nom -->
            <?php
            if ($avis['titre']) { ?>
                <p class="font-medium"><?php echo $avis['titre'] ?>&nbsp;</p>
                <?php
            }
            ?>
            <p class="text-gray-500">de</p>
            <!-- // Titre de l'avis s'il y en a un -->
            <p class="text-gray-500">&nbsp;<?php echo $membre['pseudo'] ?></p>
            <!-- Date de publication (2ème ligne) -->
            <?php
            if ($avis['date_publication']) { ?>
                <?php
                $date_publication = new DateTime($avis['date_publication']);
                $now = new DateTime();
                $interval = $date_publication->diff($now);

                if ($interval->y > 0) {
                    $time_ago = $interval->y . ' an' . ($interval->y > 1 ? 's' : '');
                } elseif ($interval->m > 0) {
                    $time_ago = $interval->m . ' mois';
                } elseif ($interval->d > 7) {
                    $weeks = floor($interval->d / 7);
                    $time_ago = $weeks . ' semaine' . ($weeks > 1 ? 's' : '');
                } elseif ($interval->d > 0) {
                    $time_ago = $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
                } else {
                    $time_ago = 'aujourd\'hui';
                }
                ?>
                <p class="grow text-gray-500 text-small">
                    &nbsp;<?php echo ($time_ago == 'aujourd\'hui') ? $time_ago : 'il y a ' . $time_ago ?></p>
                <?php
            }
            ?>
        </div>
        <!-- TAB PC Note sur 5 -->
        <div class="flex gap-1 grow shrink-0 text-small hidden md:flex">
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
        <div class="self-end ml-auto">
            <?php
            if (!$is_mon_avis) {
                ?>
                <!-- Drapeau de signalement -->
                <i class="fa-regular fa-flag text-h3 hover:text-primary hover:cursor-pointer"
                    onclick="document.getElementById('pop-up-signalement-<?php echo $id_avis ?>').classList.remove('hidden')"></i>
                <div id="pop-up-signalement-<?php echo $id_avis ?>"
                    class="z-30 fixed top-0 left-0 h-full w-full flex hidden items-center justify-center">
                    <!-- Background blur -->
                    <div class="fixed top-0 left-0 w-full h-full bg-blur/25 backdrop-blur"
                        onclick="document.getElementById('pop-up-signalement-<?php echo $id_avis ?>').classList.add('hidden');">
                    </div>
                    <!-- La pop-up (vue)-->
                    <?php
                        require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/pop_up_signalement_view.php';
                    ?>
                </div>
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
    </div>

    <!-- TEL Note sur 5 -->
    <div class="flex gap-1 grow shrink-0 text-small md:hidden">
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

    <!-- Notes complémentaires d'un restaurant) -->
    <?php
    // Notes pour les restaurants
    if ($restauration) { ?>
        <div class='flex md:flex-row flex-col justify-between flex-wrap'>
            <?php require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
            $stmt = $dbh->prepare("SELECT * FROM sae_db._avis_restauration_note WHERE id_avis = :id_avis AND id_restauration = :id_restauration");
            $stmt->bindParam(":id_avis", $id_avis);
            $stmt->bindParam(":id_restauration", $restauration['id_offre']);
            $stmt->execute();
            $notes_restauration = $stmt->fetch();

            foreach (['note_ambiance', 'note_service', 'note_cuisine', 'rapport_qualite_prix'] as $nom_note) {
                ?>
                <div class='flex text-small'>
                    <p class="text-gray-500"><?php echo ucfirst(to_nom_note(nom_attribut_note: $nom_note)) ?> :&nbsp;</p>
                    <p><?php echo $notes_restauration[$nom_note] ?>/5</p>

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
            <p class="text-gray-500 text-small">Vécu le
                <?php
                $date_experience = date('d/m/Y', strtotime($avis['date_experience']));
                echo $date_experience;
                ?>,
                <?php echo (isset($avis['contexte_passage'])) ? $avis['contexte_passage'] : '' ?>
                <?php
                setlocale(LC_TIME, 'fr_FR.UTF-8');
                ?>
            </p>
        </div>
        <?php
    }
    ?>

    <!-- Commentaire de l'avis s'il y en a un -->
    <div class="flex flex-col gap-2">
        <p class="text-small text-justify"><?php echo $avis['commentaire'] ?></p>
        <div class="flex flex-row-reverse gap-4 ">
            <i class="cursor-pointer fa-regular fa-thumbs-down text-h2 mt-1" id="tdown-<?php echo $id_avis ?>"></i>
            <i class="cursor-pointer fa-regular fa-thumbs-up text-h2 mb-1" id="tup-<?php echo $id_avis ?>"></i>
        </div>
    </div>
    <hr>
</div>

<?php if (true) { ?>
    <script>
        const thumbUp<?php echo $id_avis ?> = document.getElementById("tup-<?php echo $id_avis ?>");
        const thumbDown<?php echo $id_avis ?> = document.getElementById("tdown-<?php echo $id_avis ?>");

        toggleThumbs(thumbUp<?php echo $id_avis ?>, thumbDown<?php echo $id_avis ?>);
    </script>
<?php }