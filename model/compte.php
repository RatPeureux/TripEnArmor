<?php

class Compte extends BDD {

    private $nom_table = "_compte";

    static function getCompteById($id){
        $query = "SELECT * FROM " . self::$nom_table ." WHERE compte_id = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function createCompte($email, $mdp, $tel, $adresseId) {
        $query = "INSERT INTO (email, mdp_hash, num_tel, adresse_id". self::$nom_table ."VALUES (?, ?, ?, ?) RETURNING id_compte";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        
        if($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de créer le compte";
            return -1;
        }
    }
}

?>