<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Requêtes GET nécessaires pour pouvoir fonctioner avec AJAX
if (isset($_GET['numero_facture'])) {
    $numero_facture = $_GET['numero_facture'];
    require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/facture_view.php';
} else {
    echo "Erreur lors du chargement de la facture n°$numero_facture";
}
