<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';

$id_membre = $_SESSION['id_membre'];
$id_avis = $_GET['id_avis'];
$action = $_GET['action'];

if ($action == 'up' || $action == 'down') {
    // Requête SQL pour insérer une nouvelle réaction
    $query = "INSERT INTO " . "_avis_reactions" . " (id_membre, id_avis, type_de_reaction) VALUES (?, ?, ?)";
    
    // Prépare la requête SQL
    $statement = self::$dbh->prepare($query);
    $statement->bindParam(1, $id_membre);
    $statement->bindParam(2, $id_avis);
    if ($action == 'up') {
        $statement->bindParam(3, true);
    } else {
        $statement->bindParam(3, false);
    }
    
    // Exécute la requête et retourne les résultats ou une erreur
    if ($statement->execute()) {
        return $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "ERREUR : Impossible de créer la réaction";
        return -1;
    }
}

if ($action == 'upTOdown' || $action == 'downTOup') {
    // Requête SQL pour insérer une nouvelle réaction
    $query = "UPDATE " . "_avis_reactions" . " SET type_de_reaction = ? WHERE id_membre = ? AND id_avis = ?";
    
    // Prépare la requête SQL
    $statement = self::$dbh->prepare($query);
    if ($action == 'upTOdown') {
        $statement->bindParam(1, false);
    } else {
        $statement->bindParam(1, true);
    }
    $statement->bindParam(2, $id_membre);
    $statement->bindParam(3, $id_avis);
    
    // Exécute la requête et retourne les résultats ou une erreur
    if ($statement->execute()) {
        return $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "ERREUR : Impossible de modifier la réaction";
        return -1;
    }
}

if ($action == 'null') {
    // Requête SQL pour insérer une nouvelle réaction
    $query = "DELETE FROM " . "_avis_reactions" . " WHERE id_membre = ? AND id_avis = ?";
    
    // Prépare la requête SQL
    $statement = self::$dbh->prepare($query);
    $statement->bindParam(1, $id_membre);
    $statement->bindParam(2, $id_avis);
    
    // Exécute la requête et retourne les résultats ou une erreur
    if ($statement->execute()) {
        return $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "ERREUR : Impossible de supprimer la réaction";
        return -1;
    }
}

if (isset($_SERVER['HTTP_REFERER'])) {
    // Revenir à la page sur laquelle on était
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Si il est impossible de connaître la page précédente, alors revenir sur la page de l'offre où il y avait l'avis
    header("location: /offre?id=$id_offre");
    exit();
}