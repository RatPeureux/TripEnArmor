<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Prestation extends BDD
{
    private $nom_table = "sae_db._prestation";

    static function getPrestationByName($name)
    {
        $query = "SELECT * FROM " . self::$nom_table . " WHERE nom = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $name);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette prestation";
            return -1;
        }
    }

    static function getPrestationById($id)
    {
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_prestation = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cette prestation";
            return -1;
        }
    }

    static function createPrestation($nom, $isIncluded)
    {
        $query = "INSERT INTO " . self::$nom_table . "(nom, is_included) VALUES (?, ?) RETURNING id_prestation";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $isIncluded);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['id_prestation'];
        } else {
            echo "ERREUR : Impossible de créer la prestation";
            return -1;
        }
    }

    static function updatePrestation($id, $nom, $isIncluded)
    {
        $query = "UPDATE " . self::$nom_table . " SET nom = ?, is_included = ? WHERE id_prestation = ? RETURNING id_prestation";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $isIncluded);
        $stmt->bindParam(3, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["id_prestation"];
        } else {
            echo "ERREUR : Impossible de mettre à jour la prestation";
            return -1;
        }
    }

    static function deletePrestation($id)
    {
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_prestation = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        return $stmt->execute();
    }
}