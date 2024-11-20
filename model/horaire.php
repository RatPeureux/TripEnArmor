<?php

class Horaire extends BDD {
    private $nom_table = "_horaire";

    static function getHoraireById($id) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE horaire_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette horaire";
            return -1;
        }
    }

    static function createHoraire($ouverture, $fermeture, $pause_debut, $pause_fin) {
        $query = "INSERT INTO " . self::$nom_table ." (ouverture, fermeture, pause_debut, pause_fin) VALUES (?, ?, ?, ?) RETURNING horaire_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $ouverture);
        $statement->bindParam(2, $fermeture);
        $statement->bindParam(3, $pause_debut);
        $statement->bindParam(4, $pause_fin);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer l'horaire";
            return -1;
        }
    }

    static function updateHoraire($ouverture, $fermeture, $pause_debut, $pause_fin) {
        $query = "UPDATE " . self::$nom_table ." SET ouverture = ?, fermeture = ?, pause_debut = ?, pause_fin = ? RETURNING horaire_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $ouverture);
        $statement->bindParam(2, $fermeture);
        $statement->bindParam(3, $pause_debut);
        $statement->bindParam(4, $pause_fin);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour l'horaire";
            return -1;
        }
    }

    static function deleteHoraire($id) {
        $query = "DELETE FROM " . self::$nom_table ." WHERE horaire_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer l'horaire";
            return -1;
        }
    }
}