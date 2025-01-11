<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class PeriodesEnLigne extends BDD
{
    static private $nom_table = "sae_db._periodes_en_ligne";

    static function getAllPeriodesEnLigneByIdOffre($id_offre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible d'obtenir les periodes en ligne pour l'offre avec id n°$id_offre";
            return false;
        }
    }

    static function createPeriodeEnLigne($id_offre, $type_offre, $date_debut = false, $date_fin = false)
    {
        self::initBDD();
        if (!$date_fin) {
            $query = "INSERT INTO " . self::$nom_table . "";
        } else {
            $query = "INSERT INTO _periodes_en_ligne(id_offre, type_offre, date_fin)
                      VALUES (1, 'Premium', NULL)";
        }


        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $date_experience);
        $statement->bindParam(3, $id_membre);
        $statement->bindParam(4, $id_offre);
        $statement->bindParam(5, $note);
        $statement->bindParam(6, $contexte_passage);
        $statement->bindParam(7, $commentaire);
        $statement->bindParam(8, $id_PeriodesEnLigne_reponse);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de créer cet PeriodesEnLigne";
            return false;
        }


    }

    static function updatePeriodesEnLigne($id_PeriodesEnLigne, $titre, $commentaire, $date_experience, $id_membre, $id_offre, $id_PeriodesEnLigne_reponse)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET titre = ?, commentaire = ?, date_experience = ?, id_membre = ?, id_offre = ?, id_PeriodesEnLigne_reponse = ? WHERE id_PeriodesEnLigne = ? RETURNING id_PeriodesEnLigne";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $commentaire);
        $statement->bindParam(3, $date_experience);
        $statement->bindParam(4, $id_membre);
        $statement->bindParam(5, $id_offre);
        $statement->bindParam(6, $id_PeriodesEnLigne_reponse);
        $statement->bindParam(7, $id_PeriodesEnLigne);

        if ($statement->execute()) {
            return $statement->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de mettre à jour cet PeriodesEnLigne";
            return false;
        }
    }

    static function deletePeriodesEnLigne($id_PeriodesEnLigne)
    {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_PeriodesEnLigne = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_PeriodesEnLigne);

        return $statement->execute();
    }

}