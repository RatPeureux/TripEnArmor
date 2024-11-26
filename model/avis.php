<?php

require_once "bdd.php";

class Avis extends BDD
{
    /*
    Idée d'amélioration :
    Ajouter des valeurs statiques pour représenter les erreurs
    */
    static private $nom_table = "sae_db._avis";

    static function getAvisById($id)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_avis = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible d'obtenir cet avis";
            return false;
        }
    }

    static function getAvisByIdOffre($idOffre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $idOffre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible d'obtenir cet avis";
            return false;
        }
    }

    static function getAvisByIdMembre($idMembre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_membre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $idMembre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible d'obtenir cet avis";
            return false;
        }
    }

    static function createAvis($titre, $commentaire, $date_experience, $id_compte, $id_offre, $id_avis_reponse = null)
    {
        self::initBDD();

        $query = "INSERT INTO " . self::$nom_table . " (titre, commentaire, date_experience, id_compte, id_offre, id_avis_reponse) VALUES (?, ?, ?, ?, ?, ?) RETURNING id_avis";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $commentaire);
        $statement->bindParam(3, $date_experience);
        $statement->bindParam(4, $id_compte);
        $statement->bindParam(5, $id_offre);
        $statement->bindParam(6, $id_avis_reponse);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de créer cet avis";
            return false;
        }
    }

    static function updateAvis($id_avis, $titre, $commentaire, $date_experience, $id_compte, $id_offre, $id_avis_reponse)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET titre = ?, commentaire = ?, date_experience = ?, id_compte = ?, id_offre = ?, id_avis_reponse = ? WHERE id_avis = ? RETURNING id_avis";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $commentaire);
        $statement->bindParam(3, $date_experience);
        $statement->bindParam(4, $id_compte);
        $statement->bindParam(5, $id_offre);
        $statement->bindParam(6, $id_avis_reponse);
        $statement->bindParam(7, $id_avis);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de mettre à jour cet avis";
            return false;
        }
    }

    static function deleteAvis($id_avis)
    {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_avis = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_avis);

        return $statement->execute();
    }

}