<?php
session_start();

// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Requêtes GET nécessaires pour pouvoir fonctioner avec AJAX
if (isset($_GET['id_offre']) && isset($_GET['idx_avis']) && isset($_GET['id_membre'])) {
    $id_offre = $_GET['id_offre'];
    $idx_avis = $_GET['idx_avis'];

    // Id du membre qui charge les avis (pour afficher son avis de manière spéciale)
    $id_membre = $_GET['id_membre'];

    // Requête (adaptative) SQL retournant les informations des prochains avis (selon $idx_avis et que nous soyons un membre avec un avis posté)
    $query = "SELECT * FROM sae_db._avis WHERE id_offre = :id_offre AND fin_blacklistage IS NULL ";
    if ($id_membre != '-1') {
        $query = $query . "AND id_membre != :id_membre ";
    }
    $query = $query . "ORDER BY est_lu LIMIT 4 OFFSET :idx_avis";

    $stmt = $dbh->prepare($query);

    if ($id_membre != '-1') {
        $stmt->bindParam(':id_membre', $id_membre);
    }
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->bindParam(':idx_avis', $idx_avis);
    $stmt->execute();
    $avis_loaded = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $avis_count = count($avis_loaded);
    $response = [
        'avis_html' => '',
        'avis_count' => $avis_count
    ];
    // Renvoyer les avis (sous format de carte grâce à la vue avis_view)
    foreach ($avis_loaded as $idx => $avis) {
        // Charger les informations de l'avis pour les utiliser dans la vue
        $id_avis = $avis['id_avis'];
        $id_membre = $avis['id_membre'];
        // Charger le contenu de la vue dans un variable $carte_content
        $mode = 'avis';
        ob_start();
        require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
        $response['avis_html'] .= ob_get_clean();
    }
}

echo json_encode($response);
