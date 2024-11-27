<?php

session_start();

?>

<?php

include dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/avis_controller.php';

$avisController = new AvisController;



$titre = isset($_POST['titre']) ? $_POST['titre'] : (isset($_SESSION['titre']) ? $_SESSION['titre'] : '');
$description = isset($_POST['description']) ? $_POST['description'] : (isset($_SESSION['description']) ? $_SESSION['description'] : '');
// $note = $_POST['note'];
$date_experience = isset($_POST['date_experience']) ? $_POST['date_experience'] : (isset($_SESSION['date_experience']) ? $_SESSION['date_experience'] : '');
$date_experience = date('Y-m-d H:i:s', strtotime($date_experience));
$id_compte = isset($_POST['id_compte']) ? $_POST['id_compte'] : (isset($_SESSION['id_compte']) ? $_SESSION['id_compte'] : '');
$id_membre = isset($_POST['id_membre']) ? $_POST['id_membre'] : (isset($_SESSION['id_membre']) ? $_SESSION['id_membre'] : '');
$id_offre = isset($_POST['id_offre']) ? $_POST['id_offre'] : (isset($_SESSION['id_offre']) ? $_SESSION['id_offre'] : '');


if($avisController->createAvis($titre, $description, $date_experience, $id_membre, $id_offre)){
    echo "Test d'insertion d'un avis (OK)";
} else {
    echo "ERREUR: Impossible de créer l'avis";
}


