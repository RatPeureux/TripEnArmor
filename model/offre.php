<?php

class Offre extends BDD {
    // Nom de la table utilisée pour les opérations sur les offres
    private $nom_table = "_offre";

    static function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id) {
        // Requête SQL pour insérer une nouvelle offre
        $query = "INSERT INTO " . self::$nom_table . " (titre, description, resume, prix_mini, id_pro, type_offre_id, adresse_id) VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING offre_id";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $type_offre_id);
        $statement->bindParam(7, $adresse_id);
        $statement->bindParam(8, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour l'offre";
            return -1;
        }
    }

    static function getOffreById($id, $enLigne = true) { // R
        // Requête SQL pour sélectionner une offre par son ID et son état en ligne
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id = ? AND est_en_ligne = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id); // évite les injections SQL
        $statement->bindValue(2, $enLigne); // valeur booléenne pour est_en_ligne

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
            /*
            Exemple de retour possible :
            {
                offre_id: 3,
                est_en_ligne: false,
                ...
            }
            */
        } else {
            echo "ERREUR : Impossible d'obtenir cette offre";
            return -1;
        }
    }
}
