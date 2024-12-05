<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class RestaurationTypeRepas extends BDD
{

    static private $nom_table = "sae_db._restaurant_type_repas";

    static function getTypesRepasBydIdRestaurant($id_offre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function getRestaurantsByIdTypesRepas($id_type_repas)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_type_repas = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_type_repas);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function checkIfLinkExists($id_offre, $id_type_repas)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ? AND id_type_repas = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_type_repas);

        if ($statement->execute()) {
            return count($statement->fetchAll(PDO::FETCH_ASSOC)) != 0;
        } else {
            return false;
        }
    }

    static function createRestaurantTypeRepas($id_offre, $id_type_repas)
    {
        $query = "INSERT INTO " . self::$nom_table . " (id_offre, id_type_repas) VALUES (?, ?) RETURNING *";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_type_repas);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            return -1;
        }
    }

    static function deleteRestaurantTypeRepas($id_offre, $id_type_repas)
    {
        self::initBDD();
        $query = "DELETE FROM" . self::$nom_table . "WHERE id_offre = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_type_repas);

        return $statement->execute();
    }
}
?>