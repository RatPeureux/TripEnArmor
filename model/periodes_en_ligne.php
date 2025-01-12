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

    static function createPeriodeEnLigne($id_offre, $type_offre, $prix_ht)
    {
        self::initBDD();
            $query = "INSERT INTO sae_db._periodes_en_ligne(id_offre, type_offre, prix_ht)
                      VALUES (?, ?, ?)";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $type_offre);
        $stmt->bindParam(3, $prix_ht);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de créer la période en ligne pour l'offre d'identifiant n°$id_offre";
            return false;
        }
    }

    static function clorePeriodeByIdOffre($id_offre)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET date_fin = CURRENT_DATE
                                                WHERE id_offre = ?
                                                AND date_fin IS NULL";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "ERREUR: Impossible de clore la période en cours pour l'offre n°$id_offre";
            return false;
        }
    }

    static function ouvrirPeriodeByIdOffre($id_offre) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET date_fin = NULL
                                                WHERE id_offre = ?
                                                AND date_fin = (
                                                    SELECT MAX(date_fin)
                                                    FROM sae_db._periodes_en_ligne
                                                    WHERE id_offre = ?
                                                )";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $id_offre);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "ERREUR: Impossible d'ouvrir la période en cours pour l'offre n°$id_offre";
            return false;
        }
    }

    static function getLastDateFinByIdOffre($id_offre) {
        self::initBDD();
        $query = "select date_fin from sae_db._periodes_en_ligne
                where id_offre = ?
                order by date_fin IS NULL DESC, date_fin DESC
                limit 1;";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['date_fin'];
        } else {
            echo "ERREUR: Impossible d'obtenir la dernière date de fin de période pour l'offr n°$id_offre";
            return false;
        }
    }
}
