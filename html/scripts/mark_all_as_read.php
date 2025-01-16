<?php

if (isset($_POST['id_pro'])) {
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
    $avisController = new AvisController();

    var_dump($avisController->marquerTousLesAvisCommeLus($_POST['id_pro']));
}