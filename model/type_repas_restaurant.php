<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TypeRepasRestaurant extends BDD
{

    static private $nom_table = "sae_db.vue_restaurant_type_repas";

    static function getTypeRepasRestaurantById($id)
    {

        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce type repas";
            return -1;
        }
    }

    static function getTypesRepasRestaurantByName($name)
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

    static function createTypeRepasRestaurant($nom_type_repas)
    {

        $query = "INSERT INTO " . self::$nom_table . " (nom) VALUES (?) RETURNING id_type_repas_restaurant";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom_type_repas);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer le type de repas";
            return -1;
        }

    }

    static function updateTypeRepasRestaurant($id, $nom_type_repas = false)
    {
        if ($nom_type_repas === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $typeRepas = self::getTypeRepasRestaurantById($id);

            $query = "UPDATE " . self::$nom_table . " SET nom = ? WHERE id_offre = ? RETURNING id_offre";

            $stmt = self::$db->prepare($query);
            $stmt->bindParam(1, $nom_type_repas);
            $stmt->bindParam(2, $id);

            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["id_offre"];
            } else {
                echo "ERREUR : Impossible de mettre à jour le type de repas";
                return -1;
            }
        }
    }
}