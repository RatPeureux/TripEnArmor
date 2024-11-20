<?php

class Offre extends BDD {
    private $nom_table = "_offre";

    static function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id) { // C
        self::initBDD();
        $query = "INSERT INTO (titre, description, resume, prix_mini, id_pro, type_offre_id, adresse_id". self::$nom_table ."VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $type_offre_id);
        $statement->bindParam(7, $adresse_id);
        
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de créer l'offre";
            return -1;
        }
    }

    static function getOffreById($id, $enLigne = true) { // R
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE offre_id = ? AND est_en_ligne = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id); // éviter les injections SQL (Gros pbs)
        $statement->bindParam(2, $enLigne);

        if ($statement->execute()) {
            // exécution a fonctionnée
            return $statement->fetchAll(PDO::FETCH_ASSOC);
            /*
            {
                offre_id: 3
                est_en_ligne: false
                ...
            }
            */
        } else {
            // exécution n'a pas fonctionnée
            echo "ERREUR";
            return false;
        }
    }

    static function updateOffre($id, $titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id) { // U
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET titre = ?, description = ?, resume = ?, prix_mini = ?, id_pro = ?, type_offre_id = ?, adresse_id = ?, date_mise_a_jour = CURRENT_TIMESTAMP WHERE offre_id = ? RETURNING offre_id";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $type_offre_id);
        $statement->bindParam(7, $adresse_id);
        $statement->bindParam(8, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de mettre à jour l'offre";
            return -1;
        }
    }

    static function deleteOffre($id) { // D
        self::initBDD();
        $query = "DELETE FROM ". self::$nom_table . "WHERE offre_id = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return true;
        } else {
            echo "ERREUR: Impossible de supprimer l'offre";
            return false;
        }
    }
}