<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Souscription extends BDD
{
    static private $nom_table = "sae_db._souscription";

    static function getSouscriptionById($id_souscription)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_souscription = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_souscription);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce spectacle";
            return -1;
        }
    }

    static function getAllSouscriptionsByIdOffre($id_offre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce spectacle";
            return -1;
        }
    }

    static function createSouscription($id_offre, $nom_option, $prix_ht, $prix_ttc, $date_lancement, $nb_semaines)
    {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (id_offre, nom_option, prix_ht, prix_ttc, date_lancement, nb_semaines) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $nom_option);
        $stmt->bindParam(3, $prix_ht);
        $stmt->bindParam(4, $prix_ttc);
        $stmt->bindParam(5, $date_lancement);
        $stmt->bindParam(6, $nb_semaines);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "ERREUR : Impossible de créer une souscription pour l'offre d'identifiant n°$id_offre";
            return -1;
        }
    }

    static function updateSpectacle($id_souscription, $value)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET date_annulation = ? WHERE id_souscription = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $value);
        $statement->bindParam(2, $id_souscription);

        if ($statement->execute()) {
            return true;
        } else {
            echo "ERREUR : Impossible de mettre à jour la période d'identifiant n°$id_souscription";
            return -1;
        }
    }
}
