<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class VisiteLangue extends BDD
{

    static private $nom_table = "sae_db._visite_langue";

    static function getLanguesBydIdVisite($id_offre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function getVisitesByIdLangue($id_langue)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_langue = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_langue);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }

    static function checkIfLinkExists($id_offre, $id_langue)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ? AND id_langue = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_langue);

        print_r($statement->fetchAll(PDO::FETCH_ASSOC));

        if ($statement->execute()) {
            return count($statement->fetchAll(PDO::FETCH_ASSOC)) != 0;
        } else {
            return false;
        }
    }

    static function createVisiteLangue($id_offre, $id_langue)
    {
        $query = "INSERT INTO (id_offre, id_langue" . self::$nom_table . " VALUES (?, ?) RETURNING *";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_langue);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            return -1;
        }
    }

    static function deleteVisiteLangue($id_offre, $id_langue)
    {
        self::initBDD();
        $query = "DELETE FROM" . self::$nom_table . " WHERE id_offre = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);
        $statement->bindParam(2, $id_langue);

        return $statement->execute();
    }
}
?>