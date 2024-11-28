<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TImageImg extends BDD {
    static private $nom_table = "T_Image_Img";

    static function getPathToPlan($id_parc) {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_parc = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_parc);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir le plan";
            return -1;
        }
    }

    static function getImageByPath($path) { 
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE img_path = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $path);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette image";
            return -1;
        }
    }

    static function createImage($path) {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (img_path) VALUES (?) RETURNING img_path";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $path);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer l'image";
            return -1;
        }
    }

    static function updateImage($path, $new_path) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table ." SET img_path = ? WHERE img_path = ? RETURNING img_path";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $new_path);
        $statement->bindParam(2, $path);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour l'image";
            return -1;
        }
    }

    static function deleteImage($path) {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table ." WHERE img_path = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
