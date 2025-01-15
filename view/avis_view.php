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
<div class="avis w-full <?php echo $is_mon_avis ? 'border-primary border-4' : '' ?> p-2 flex flex-col gap-1 text-small">
    <?php
    // Obtenir la variables regroupant les infos du membre
    $membre = $membreController->getInfosMembre($id_membre);
    $avis = $avisController->getAvisById($id_avis);
    $restauration = $restaurationController->getInfosRestauration($avis['id_offre']);

    // Vérifier si on est connecté avec le compte du pro qui peut répondre
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
    $stmt = $dbh->prepare("SELECT id_pro FROM sae_db._offre WHERE id_offre = :id_offre");
    $stmt->bindParam(':id_offre', $avis['id_offre']);
    $stmt->execute();
    $id_pro_must_have = $stmt->fetch(PDO::FETCH_ASSOC)['id_pro'];

    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
    $pro_can_answer = (isConnectedAsPro() && $id_pro_must_have == $_SESSION['id_pro']) ? true : false;

    // Vérifier si celui qui consulte l'avis est le pro lié à l'offre correspondant à l'avis -> mettre l'attribut est_lu à true
    if ($pro_can_answer) {
        $stmt = $dbh->prepare("UPDATE sae_db._avis SET est_lu = TRUE WHERE id_avis = ?");
        $stmt->bindParam(1, $id_avis);
        $stmt->execute();
    }
    ?>

    <!-- Première ligne du haut -->
    <div class="flex gap-3 items-center">
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
                <p class="grow text-gray-500">
                    &nbsp;<?php echo ($time_ago == 'aujourd\'hui') ? $time_ago : 'il y a ' . $time_ago ?></p>
                <?php
            }
            ?>
        </div>
        <!-- TAB PC Note sur 5 -->
        <div class="flex gap-1 grow shrink-0 hidden md:flex">
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
    <div class="flex gap-1 grow shrink-0 md:hidden">
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
                <div class='flex'>
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
            <p class="text-gray-500">Vécu le
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
        <p class="text-justify"><?php echo $avis['commentaire'] ?></p>
    </div>

    <!-- Réponse du pro s'il y en a une -->
    <?php if(!is_null($avis['reponse'])) { ?>
        <div class="p-4">
            <!-- Bouton d'affichage de réponse -->
            <div class="flex gap-2 items-center text-gris hover:cursor-pointer" onclick="this.querySelector('i').classList.toggle('rotate-90'); document.getElementById('reponse-avis-<?php echo $id_avis ?>').classList.toggle('hidden');">
                <i class="fa-solid fa-angle-right"></i>
                <p>Réponse du pro</p>
            </div>
    
            <!-- Texte de la réponse -->
            <p id="reponse-avis-<?php echo $id_avis ?>" class="hidden italic"> <?php echo $avis['reponse']?> </p>
        </div>
    <!-- Sinon formulaire de reponse pour le pro s'il est bien connecté -->
    <?php } else if ($pro_can_answer) { ?>
        <div class="p-4 flex flex-col gap-2 justify-start">
            <!-- Bouton de rédaction de réponse -->
            <div class="flex gap-4 items-center">
                <a class="p-1 hover:cursor-pointer self-start border border-primary" onclick="document.getElementById('formulaire-reponse-avis-<?php echo $id_avis ?>').classList.toggle('hidden')">Répondre</a>
                <i class="fa-regular fa-paper-plane hover:cursor-pointer" title="Envoyer" onclick="
                    let content = document.getElementById('formulaire-reponse-avis-<?php echo $id_avis ?>').value;
                    let encodedContent = encodeURIComponent(content);
                    window.location.href = '/scripts/send_reponse.php?id_avis=<?php echo $id_avis ?>&reponse=' + encodedContent;">
                </i>
            </div>
    
            <!-- Champ de rédaction -->
            <textarea id="formulaire-reponse-avis-<?php echo $id_avis ?>" class="hidden border border-gris"></textarea>
        </div>
    <?php } ?>

    <hr>
</div>

<?php if (true) { ?>
    <script>
        const thumbUp<?php echo $id_avis ?> = document.getElementById("tup-<?php echo $id_avis ?>");
        const thumbDown<?php echo $id_avis ?> = document.getElementById("tdown-<?php echo $id_avis ?>");

        toggleThumbs(thumbUp<?php echo $id_avis ?>, thumbDown<?php echo $id_avis ?>);
    </script>
<?php }
