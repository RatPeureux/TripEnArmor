<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TypeRepas extends BDD
{

    static private $nom_table = "sae_db._type_repas";

    static function getTypeRepasById($id)
    {

        $query = "SELECT * FROM " . self::$nom_table . " WHERE type_repas_id = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir ce type repas";
            return -1;
        }
    }

    static function getTypesRepasByName($name)
    {

        $query = "SELECT * FROM " . self::$nom_table . " WHERE nom = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $name);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce type repas";
            return -1;
        }
    }

    static function createTypeRepas($nom_type_repas)
    {

        $query = "INSERT INTO " . self::$nom_table . "(nom) VALUES (?) RETURNING type_repas_id";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom_type_repas);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['type_repas_id'];
        } else {
            echo "ERREUR : Impossible de créer le type de repas";
            return -1;
        }

    }

    static function updateTypeRepas($nom_type_repas)
    {
        $query = "UPDATE " . self::$nom_table . " SET nom = ? RETURNING type_repas_id";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom_type_repas);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["type_repas_id"];
        } else {
            echo "ERREUR : Impossible de mettre à jour le type repas";
            return -1;
        }
    }

    static function deleteTag($id)
    {
        $query = "DELETE FROM " . self::$nom_table . " WHERE type_repas_id = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        return $stmt->execute();
    }

}