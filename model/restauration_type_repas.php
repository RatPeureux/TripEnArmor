<?php

class RestaurationTypeRepas extends BDD {

    private $nom_table = "sae_db._restaurant_type_repas";

    function getTypesRepasBydIdRestaurant($id_offre){
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        if ($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR";
            return false;
        }
    }

    function getRestaurantByIdTypesRepas($id_type_repas){
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_type_repas = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_type_repas);

        if ($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR";
            return false;
        }
    }

    function checkIfLinkExists($id_offre, $id_type_repas) {
        self::initBDD();
        $query = "SELECT * FROM ". self::$nom_table ." WHERE id_offre = ? AND id_type_repas = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_type_repas);

        if ($statement->execute()){
            return !empty($statement->fetchAll(PDO::FETCH_ASSOC)[0]);
        } else {
            return false;
        }
    }

    function createRestaurantTypeRepas($id_offre,$id_type_repas) {
        $query = "INSERT INTO (id_offre, id_type_repas". self::$nom_table ."VALUES (?, ?) RETURNING *";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_type_repas);
        
        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            return -1;
        }
    }

    function deleteRestaurantTypeRepas($id_offre, $id_type_repas) {
        self::initBDD();
        $query = "DELETE FROM". self::$nom_table ."WHERE id_offre = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_type_repas);

        return $statement->execute();
    }
}
?>