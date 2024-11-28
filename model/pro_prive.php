<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class ProPrive extends BDD
{

    private $nom_table = "sae_db._pro_prive";

    static function createProPrive($email, $mdp, $tel, $adresseId, $nom_pro, $num_siren)
    {
        self::initBDD();
        $query = "INSERT INTO (email, mdp_hash, num_tel, id_adresse, nom_pro, num_siren" . self::$nom_table . "VALUES (?, ?, ?, ?, ?, ?) RETURNING id_compte";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        $statement->bindParam(5, $nom_pro);
        $statement->bindParam(6, $num_siren);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_compte'];
        } else {
            echo "ERREUR: Impossible de créer le compte pro privé";
            return -1;
        }
    }

    static function getProPriveById($id)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_compte = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function updateProPrive($id, $email, $mdp, $tel, $adresseId, $nom_pro, $num_siren)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET email = ?, mdp_hash = ?, num_tel = ?, id_adresse = ?, $nom_pro = ?, num_siren = ? WHERE id_compte = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        $statement->bindParam(5, $nom_pro);
        $statement->bindParam(6, $num_siren);
        $statement->bindParam(7, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_compte'];
        } else {
            echo "ERREUR: Impossible de mettre à jour le compte pro privé";
            return -1;
        }
    }

    static function deleteProPrive($id)
    {
        self::initBDD();
        $query = "DELETE FROM" . self::$nom_table . "WHERE id_compte = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();

    }
}
?>