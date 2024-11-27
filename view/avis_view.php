<?php
include dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
$avisController = new AvisController;

// Test d'insertion d'un avis (OK)
// $maDate = date('2024-11-02 10:10:10');
// $avisController->createAvis("monTitre", "c nul", $maDate, $id_membre, $id_offre);
// print_r($avisController->getAvisByIdOffre($id_offre));