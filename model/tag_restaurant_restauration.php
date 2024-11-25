<?php

class tagRestaurantRestauration extends BDD
{
    private $nom_table = "_tag_restaurant_restauration";

    static function getByIdBoth($id_offre, $id_tag_restaurant){

        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . "WHERE id_offre = ? AND id_tag_restaurant = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $id_tag_restaurant);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            echo "ERREUR : Impossible d'obtenir ce tag de ce restaurant";
            return -1;
        }
    }

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

    static function getByIdTagRestau($id_tag_restaurant){

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

}
