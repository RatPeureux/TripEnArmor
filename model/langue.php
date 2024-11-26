<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/bdd.php";

class Langue extends BDD {
    private $nom_table = "sae_db._langue";

    static function getLangueById($id) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_langue = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir ce language";
            return -1;
        }
    }

    static function getLanguesByName($name) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE nom = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $name);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce language";
            return -1;
        }
    }
}
