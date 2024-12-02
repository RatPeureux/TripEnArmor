<?php
$id_avis = $_GET['id_avis'];
$id_offre = $_GET['id_offre'];

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';
$avisController = new AvisController();
$avisController->deleteAvis($id_avis);

header("location: /offre?id=$id_offre");
