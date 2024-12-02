<?php
// Connexion avec la bdd
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Requêtes GET nécessaires pour pouvoir fonctioner avec AJAX
if (isset($_GET['id_offre']) && isset($_GET['idx_avis']) && isset($_GET['id_membre'])) {
    $id_offre = $_GET['id_offre'];
    $idx_avis = $_GET['idx_avis'];

    // Id du membre qui charge les avis (pour afficher son avis de manière spéciale)
    $id_membre = $_GET['id_membre'];

    // Requête SQL retournant les informations des prochains avis (selon $idx_avis)
    if ($id_membre != '-1') {
        $stmt = $dbh->prepare("SELECT * FROM sae_db._avis WHERE id_offre = :id_offre AND id_membre != :id_membre LIMIT 3 OFFSET :idx_avis");
        $stmt->bindParam(':id_membre', $id_membre);
    } else {
        $stmt = $dbh->prepare("SELECT * FROM sae_db._avis WHERE id_offre = :id_offre LIMIT 3 OFFSET :idx_avis");
    }
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->bindParam(':idx_avis', $idx_avis);
    $stmt->execute();
    $avis_loaded = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les avis (sous format de carte grâce à la vue avis_view)
    if ($avis_loaded) {
        foreach ($avis_loaded as $idx => $avis) {
            // Charger les informations de l'avis pour les utiliser dans la vue
            $id_avis = $avis['id_avis'];
            $id_membre = $avis['id_membre'];

            // Charger le contenu de la vue dans un variable $carte_content
            ob_start();
            require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
            $carte_content = ob_get_clean();

            // Retourner le contenu de la vue de l'avis
            echo $carte_content;
        }
    }
}
