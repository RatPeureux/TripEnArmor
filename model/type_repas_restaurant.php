<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TypeRepasRestaurant extends BDD
{
    // Nom de la vue pour les types de repas liés à l'offre
    static private $nom_table = "sae_db.vue_restaurant_type_repas";  // Vue

    // Récupérer les types de repas associés à une offre
    static function getTypeRepasRestaurantById($id_offre)
    {
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir les types de repas pour cette offre";
            return -1;
        }
    }

    // Récupérer un type de repas par son nom
    static function getTypesRepasRestaurantByName($name)
    {
        $query = "SELECT * FROM " . self::$nom_table . " WHERE nom = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $name);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce type de repas";
            return -1;
        }
    }

    // Créer un type de repas dans la table _type_repas
    static function createTypeRepasRestaurant($nom_type_repas)
    {
        $query = "INSERT INTO sae_db._type_repas (nom) VALUES (?) RETURNING id_type_repas";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom_type_repas);

        if ($stmt->execute()) {
            // Retourner uniquement l'id du type de repas créé
            return $stmt->fetch(PDO::FETCH_ASSOC)['id_type_repas'];
        } else {
            echo "ERREUR : Impossible de créer le type de repas";
            return -1;
        }
    }

    // Lier un type de repas à une offre
    static function linkTypeRepasToOffre($id_offre, $id_type_repas)
    {
        $query = "INSERT INTO sae_db._restaurant_type_repas (id_offre, id_type_repas) VALUES (?, ?)";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $id_type_repas);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "ERREUR : Impossible de lier le type de repas à l'offre";
            return false;
        }
    }

    // Supprimer tous les types de repas associés à une offre
    static function deleteTypeRepasByOffre($id_offre)
    {
        $query = "DELETE FROM sae_db._restaurant_type_repas WHERE id_offre = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "ERREUR : Impossible de supprimer les types de repas pour cette offre";
            return false;
        }
    }
}
