<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class tagRestaurantRestauration extends BDD
{
    static private $nom_table = "sae_db._tag_restaurant_restauration";

    static function getByIdOffre($id_offre){

        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . "WHERE id_offre = ?";

        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            echo "ERREUR : Impossible d'obtenir l'id de offre";
            return -1;
        }
    }

    static function getByIdTagRestaurant($id_tag_restaurant){

        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . "WHERE id_tag_restaurant = ?";

        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_tag_restaurant);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            echo "ERREUR : Impossible d'obtenir l'id du tag";
            return -1;
        }
    }

    static function checkIfLinkExists($id_restaurant, $id_tag)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ? AND id_tag_restaurant = ?";

        $statement = self::$db->prepare($query);
        $statement->bindValue(1, $id_restaurant);
        $statement->bindValue(2, $id_tag);

        if ($statement->execute()) {
            return count($statement->fetchAll(PDO::FETCH_ASSOC)) != 0;
        } else {
            return -1;
        }
    }

    static function linkOffreAndTag($id_restaurant, $id_tag) {
        self::initBDD();
        $query = "INSERT INTO ". self::$nom_table . " (id_offre, id_tag_restaurant) VALUES (?, ?) RETURNING *";

        $statement = self::$db->prepare($query);
        $statement->bindValue(1, $id_restaurant);
        $statement->bindValue(2, $id_tag);
        
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            return -1;
        }
    }

    static function unlinkOffreAndTag( $id_restaurant, $id_tag)
    {
        self::initBDD();
        $query = "DELETE FROM ". self::$nom_table . " WHERE id_offre = ? AND id_tag_restaurant = ?";

        $statement = self::$db->prepare($query);
        $statement->bindValue(1, $id_restaurant);
        $statement->bindValue(2, $id_tag);

        return $statement->execute();
    }

}
