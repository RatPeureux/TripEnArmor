<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';

$avisController = new avisController();
$membreController = new MembreController();

$avis = $avisController->getAvisNonVusByIdPro($_SESSION['id_pro']);

if ($avis && count($avis) !== 0) {
    foreach ($avis as $avi) {
        ?>
        <div class="h-full p-2 hover:bg-gray-100">
            <!-- lien vers l'offre -->
            <a href='/scripts/go_to_details.php?id_offre=<?php echo $avi['id_offre'] ?>'>
                <div class="w-full flex justify-between items-center">
                    <div class="flex items-center space-x-1">
                        <p class="text-black text-lg"><?php echo $avi['titre']; ?></p>
                        <p class="text-gray-600 texxt-small ">posté par</p>
                        <p class="text-black text-sm">
                            <?php echo $membreController->getInfosMembre($avi['id_membre'])['pseudo']; ?>
                        </p>
                        <p class="text-sm text-gray-600">Le</p>
                        <p class="text-black text-sm">
                            <?php echo (new DateTime($avi['date_publication']))->format('d/m/Y'); ?>
                        </p>
                    </div>
                    <div class="flex justify-end">
                        <?php
                        // Note s'il y en a une
                        $note = floatval($avi['note']);
                        for ($i = 0; $i < 5; $i++) {
                            if ($note >= 1) {
                                ?>
                                <img class="w-3" src="/public/icones/egg-full.svg" alt="1 point de note">
                                <?php
                            } else if ($note > 0) {
                                ?>
                                    <img class="w-3" src="/public/icones/egg-half.svg" alt="0.5 point de note">
                                <?php
                            } else {
                                ?>
                                    <img class="w-3" src="/public/icones/egg-empty.svg" alt="0 point de note">
                                <?php
                            }
                            $note--;
                        }
                        ?>
                    </div>
                </div>
                <p class="text-sm">Vécu le <?php echo (new DateTime($avi['date_experience']))->format('d/m/Y'); ?></p>
                <p class="text-sm">
                    <?php
                    echo $avi['commentaire']
                        ?>
                </p>
            </a>
        </div>
        <hr class="mt-1" />
        <?php
    }
} else {
    ?>
    <div class="h-full p-2">
        <p class="text-xl">
            Vous n'avez aucune nouvelle notification.
        </p>
    </div>
    <?php
} ?>