<?php

class Facture extends BDD {
    private $nom_table = "_facture";

    static function getFactureById($id) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE facture_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette facture";
            return -1;
        }
    }

    static function createFacture($jour_en_ligne) {
        $query = "INSERT INTO " . self::$nom_table ." (jour_en_ligne) VALUES (?) RETURNING facture_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $jour_en_ligne);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer la facture";
            return -1;
        }
    }

    static function updateFacture($jour_en_ligne) {
        $query = "UPDATE " . self::$nom_table ." SET jour_en_ligne = ? RETURNING facture_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $jour_en_ligne);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour la facture";
            return -1;
        }
    }

    static function deleteFacture($id) {
        $query = "DELETE FROM " . self::$nom_table ." WHERE facture_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer la facture";
            return -1;
        }
    }

    //...
}