<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class ProPublic extends BDD
{

    static private $nom_table = "sae_db._pro_public";

    static function createProPublic($email, $mdp, $tel, $adresseId, $nom_pro, $type_orga)
    {
        $query = "INSERT INTO (email, mdp_hash, num_tel, id_adresse, nom_pro, type_orga" . self::$nom_table . "VALUES (?, ?, ?, ?, ?, ?) RETURNING id_compte";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        $statement->bindParam(5, $nom_pro);
        $statement->bindParam(6, $type_orga);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_compte'];
        } else {
            echo "ERREUR: Impossible de créer le compte pro public";
            return -1;
        }
    }

    static function getProPublicById($id)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_compte = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        
        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC)[0];
        } else {
            return -1;
        }
    }

    static function updateProPublic($id, $email, $mdp, $tel, $adresseId, $nom_pro, $type_orga)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET email = ?, mdp_hash = ?, num_tel = ?, id_adresse = ?, $nom_pro = ?, type_orga = ? WHERE id_compte = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        $statement->bindParam(5, $nom_pro);
        $statement->bindParam(6, $type_orga);
        $statement->bindParam(7, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_compte'];
        } else {
            echo "ERREUR: Impossible de mettre à jour le compte pro public";
            return -1;
        }
    }

    static function deleteProPublic($id)
    {
        self::initBDD();
        $query = "DELETE FROM" . self::$nom_table . "WHERE id_compte = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
?>