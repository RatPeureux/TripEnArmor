<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TypeOffre extends BDD
{

    static private $nom_table = "sae_db._type_offre";

    static function getTypeOffreById($id_type_offre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_type_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_type_offre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            return false;
        }
    }

    static function getAllTypeOffre() {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table;
        $statement = self::$db->prepare($query);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
}

?>