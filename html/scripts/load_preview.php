<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Requêtes GET nécessaires pour pouvoir fonctioner avec AJAX
if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];
    require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/facture_view.php';
} else {
    echo 'Erreur lors du chargement d\'une offre';
}
