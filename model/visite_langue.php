<?php

class VisiteLangue extends BDD {

    private $nom_table = "sae_db._visite_langue";

    static function createVisiteLangue($id_offre,$id_langue) {
        $query = "INSERT INTO (id_offre, id_langue". self::$nom_table ."VALUES (?, ?) RETURNING *";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_langue);
        
        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR: Impossible de créer la langue de visite";
            return -1;
        }
    }

    static function getVisiteLangueById($id){
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR";
            return false;
        }
    }
    
    static function updateVisiteLangue($id_offre, $id_langue) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET id_langue = ? WHERE id_offre = ? RETURNING *";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_langue);
        $statement->bindParam(7, $id_offre);

        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR: Impossible de mettre à jour la langue de la visite";
            return -1;
        }
    }

    static function deleteVisiteLangue($id) {
        self::initBDD();
        $query = "DELETE FROM". self::$nom_table ."WHERE id_offre = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
?>