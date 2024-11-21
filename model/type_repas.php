<?php

class TypeRepas extends BDD {

    private $nom_table = "_type_repas";

    static function getTypeRepasById($id){

        $query = "SELECT * FROM " . self::$nom_table ." WHERE type_repas_id = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce type repas";
            return -1;
        }

    }

    static function createTypeRepas($nom_type_repas){

        $query = "INSERT INTO " . self::$nom_table . "(nom_type_repas) VALUES (?) RETURNING type_repas_id";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom_type_repas);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer le type de repas";
            return -1;
        }

    }

    static function updateTypeRepas($nom_type_repas) {
        $query = "UPDATE " . self::$nom_type_repas ." SET nom_type_repas = ? RETURNING type_repas_id";
        
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom_type_repas);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le type repas";
            return -1;
        }
    }

    static function deleteTag($id) {
        $query = "DELETE FROM " . self::$nom_table ." WHERE type_repas_id = ?";
        
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer le type repas";
            return -1;
        }
    }

}