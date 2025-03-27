<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Vérifier que nous avons un compte à supprimer
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$membre = verifyMember();
if (!isset($membre['id_compte'])) {
    header('location: /401');
    exit();
}

// Vériier que la phrase
// "je veux supprimer mon compte"
// a été saisie dans le champ du formulaire
if ($_POST['textConfirmDelete'] !== 'je veux supprimer mon compte') {
    header('location: /401');
    exit();
}

// Supprimer le compte en BDD (les triggers s'occuppent de l'anonymisation etc)
$stmt = $dbh->prepare("DELETE FROM sae_db._membre WHERE id_compte = :id_compte");
$stmt->bindParam(':id_compte', $membre['id_compte']);
if ($stmt->execute()) {
    $_SESSION['message_pour_notification'] = 'Votre compte a été supprimé';
    unset($_SESSION['id_membre']);
    header('Location: /');
    exit();
} else {
    header('location: /401');
    exit();
}
