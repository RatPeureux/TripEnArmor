<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TagRestaurant extends BDD
{
    static private $nom_table = "sae_db._tag_restaurant";

    static function getTagRestaurantById($id)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_tag_restaurant = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag de restauration";
            return -1;
        }
    }

    static function getTagsRestaurantByName($name)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE nom = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $name);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag de restauration";
            return -1;
        }
    }

    static function createTagRestaurant($nom)
    {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (nom) VALUES (?) RETURNING id_tag_restaurant";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le tag de restauration";
            return -1;
        }
    }

    static function updateTagRestaurant($nom)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET nom = ? RETURNING id_tag_restaurant";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer le tag de restauration";
            return -1;
        }
    }

    static function deleteTagRestauration($id)
    {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_offre = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        return $stmt->execute();
    }
}
