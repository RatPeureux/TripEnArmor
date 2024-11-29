<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Membre extends BDD
{

    static private $nom_table = "sae_db._membre";

    static function createMembre($email, $mdp, $tel, $adresseId, $pseudo, $prenom, $nom)
    {
        self::initBDD();
        $query = "INSERT INTO (email, mdp_hash, num_tel, id_adresse, pseudo, nom, prenom" . self::$nom_table . "VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id_compte";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        $statement->bindParam(5, $pseudo);
        $statement->bindParam(6, $prenom);
        $statement->bindParam(7, $nom);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_compte'];
        } else {
            echo "ERREUR: Impossible de créer le compte membre";
            return -1;
        }
    }

    static function getMembreById($id)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_compte = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function updateMembre($id, $email, $mdp, $tel, $adresseId, $pseudo, $prenom, $nom)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET email = ?, mdp_hash = ?, num_tel = ?, id_adresse = ?, pseudo = ?, prenom = ?, nom = ? WHERE id_compte = ? RETURNING id_compte";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $email);
        $statement->bindParam(2, $mdp);
        $statement->bindParam(3, $tel);
        $statement->bindParam(4, $adresseId);
        $statement->bindParam(5, $pseudo);
        $statement->bindParam(6, $prenom);
        $statement->bindParam(7, $nom);
        $statement->bindParam(8, $id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_compte'];
        } else {
            echo "ERREUR: Impossible de mettre à jour le compte membre";
            return -1;
        }
    }

    static function deleteMembre($id)
    {
        self::initBDD();
        $query = "DELETE FROM" . self::$nom_table . "WHERE id_compte = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();

    }
}

?>