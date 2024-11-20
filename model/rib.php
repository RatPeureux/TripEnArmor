<?php

class Rib extends BDD {
    private $nom_table = "_rib";

    static function getRibById($id) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE rib_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce rib";
            return -1;
        }
    }

    static function createRib($code_banque, $code_guichet, $numero_compte, $cle_rib, $compte_id) {
        $query = "INSERT INTO " . self::$nom_table ." (code_banque, code_guichet, numero_compte, cle_rib, compte_id) VALUES (?, ?, ?, ?, ?) RETURNING rib_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $code_banque);
        $statement->bindParam(2, $code_guichet);
        $statement->bindParam(3, $numero_compte);
        $statement->bindParam(4, $cle_rib);
        $statement->bindParam(5, $compte_id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer le rib";
            return -1;
        }
    }

    static function updateRib($code_banque, $code_guichet, $numero_compte, $cle_rib, $compte_id) {
        $query = "UPDATE " . self::$nom_table ." SET code_banque = ?, code_guichet = ?, numero_compte = ?, cle_rib = ?, compte_id = ? RETURNING rib_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $code_banque);
        $statement->bindParam(2, $code_guichet);
        $statement->bindParam(3, $numero_compte);
        $statement->bindParam(4, $cle_rib);
        $statement->bindParam(5, $compte_id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le rib";
            return -1;
        }
    }

    static function deleteRib($id) {
        $query = "DELETE FROM " . self::$nom_table ." WHERE rib_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer le rib";
            return -1;
        }
    }
}