<?php

if (isset($_GET['id_pro'])) {
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
    $avisController = new AvisController();

    $avisController->marquerTousLesAvisCommeLus($_GET['id_pro']);

    echo "Tous les avis ont été marqués comme lus.";

    header("Location: /pro/");
}