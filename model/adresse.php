<?php

class Adresse extends BDD {
    private $nom_table = "_adresse";

    static function getAdresseById($id) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE adresse_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette adresse";
            return -1;
        }
    }

    static function createAdresse($code_postal, $ville, $numero, $odonyme, $complement_adresse) {
        $query = "INSERT INTO " . self::$nom_table ." (code_postal, ville, odonyme, complement_adresse) VALUES (?, ?, ?, ?, ?) RETURNING adresse_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $code_postal);
        $statement->bindParam(2, $ville);
        $statement->bindParam(3, $numero);
        $statement->bindParam(4, $odonyme);
        $statement->bindParam(5, $complement_adresse);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer l'adresse";
            return -1;
        }
    }

    static function updateAdresse($code_postal, $ville, $numero, $odonyme, $complement_adresse) {
        $query = "UPDATE " . self::$nom_table ." SET code_postal = ?, ville = ?, numero = ?, odonyme = ?, complement_adresse = ? RETURNING adresse_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $code_postal);
        $statement->bindParam(2, $ville);
        $statement->bindParam(3, $numero);
        $statement->bindParam(4, $odonyme);
        $statement->bindParam(5, $complement_adresse);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour l'adresse";
            return -1;
        }
    }

    static function deleteAdresse($id) {
        $query = "DELETE FROM " . self::$nom_table ." WHERE adresse_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer l'adresse";
            return -1;
        }
    }
}