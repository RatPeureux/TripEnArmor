<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/controller/avis_controller.php";
$avisController = new AvisController();
?>

<div class="z-30 p-5 text-black bg-white border border-black flex flex-col gap-5 max-w-[350px]">

    <!-- Information sur vos tickets concernant -->
    <h2 class="text-2xl text-center">Tickets de blacklistage</h2>

    <?php
    $nb_blacklistes_en_cours = 3 - $nb_tickets;
    if ($nb_blacklistes_en_cours == 0) { ?>
        <p>Vous avez tous vos tickets</p>
    <?php } else {
        $echeances = [];
        foreach ($blacklistes_en_cours as $b_en_cours) {
            $avis = $avisController->getAvisById($b_en_cours['id_avis']);
            $date_fin = $avis['fin_blacklistage'];
            $date = new DateTime($date_fin);

            setlocale(LC_TIME, 'fr_FR.utf8');
            $date_fin = strftime('%d %B %Y à %H:%M:%S', $date->getTimestamp()); // Format in "29 mars 2025 14:45:59"
    
            if (!isset($echeances[$date_fin])) {
                $echeances[$date_fin] = 1;
            } else {
                $echeances[$date_fin] += 1;
            }
        }
        foreach ($echeances as $date => $nb_t) { ?>
            <div>
                <p>Le <?php echo $date ?> :</p>
                <p class='ml-5'>Vous récupérerez <?php echo $nb_t ?> ticket(s)</p>
            </div>
            <?php
        }
        ?>
        <p class="italic text-small">Ou lorsque l'un des avis blacklistés sera supprimé par l'utilisateur</p>
        <?php
    }
    ?>
</div>