<?php

class Tag extends BDD {
    private $nom_table = "_tag";

    static function getTagById($id) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE tag_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag";
            return -1;
        }
    }

    static function createTag($nom_tag) {
        $query = "INSERT INTO " . self::$nom_table ." (nom_tag) VALUES (?) RETURNING tag_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $nom_tag);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer le tag";
            return -1;
        }
    }

    static function updateTag($nom_tag) {
        $query = "UPDATE " . self::$nom_table ." SET nom_tag = ? RETURNING tag_id";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $nom_tag);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le tag";
            return -1;
        }
    }

    static function deleteTag($id) {
        $query = "DELETE FROM " . self::$nom_table ." WHERE tag_id = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer le tag";
            return -1;
        }
    }
}