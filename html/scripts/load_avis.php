<?php
// Connexion avec la bdd
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

// Requêtes GET nécessaires pour pouvoir fonctioner avec AJAX
if (isset($_GET['id_offre']) && isset($_GET['idx_avis'])) {
    $id_offre = $_GET['id_offre'];
    $idx_avis = $_GET['idx_avis'];

    // Requête SQL retournant les informations des prochains avis (selon $idx_avis)
    $stmt = $dbh->prepare("SELECT * FROM sae_db._avis WHERE id_offre = :id_offre LIMIT 3 OFFSET :idx_avis");
    $stmt->bindParam(':id_offre', $id_offre);
    $stmt->bindParam(':idx_avis', $idx_avis);
    $stmt->execute();
    $avis_loaded = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les avis (sous format de carte grâce à la vue avis_view)
    // !!! IN WORK !!! : reste à renvoyer sous forme de carte et non sous format json
    if ($avis_loaded) {
        // Afficher les [] manuellement autour des avis pour parse en JSON
        echo '[';
        foreach ($avis_loaded as $idx => $avis) {

            // Charger les informations de l'avis pour les utiliser dans la vue
            $id_avis = $avis['id_avis'];
            $date_publication = $avis['date_publication'];
            $date_experience = $avis['date_experience'];
            $commentaire = $avis['commentaire'];
            $id_avis_reponse = $avis['id_avis_reponse'];
            $id_compte = $avis['id_compte'];
            $titre = $avis['titre'];

            // Charger le contenu de la vue dans un variable $carte_content
            ob_start();
            require dirname($_SERVER['DOCUMENT_ROOT']) . '/view/avis_view.php';
            $carte_content = ob_get_clean();

            // Retourner le contenu de la vue de l'avis
            // echo json_encode($carte_content);

            echo json_encode($avis);
            if ($idx < 2) {
                echo ',';
            }
        }
        echo ']';
    } else {
        echo json_encode([]);
    }
}
