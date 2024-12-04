<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Visite extends BDD
{
    static private $nom_table = "sae_db._visite";

    static function getVisiteById($id, $online = true)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ? AND est_en_ligne = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindValue(2, $online);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cette visite";
            return -1;
        }
    }

    static function createVisite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $avec_guide)
    {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (est_en_ligne, description, resume, prix_mini, titre, id_pro, id_type_offre, id_adresse, duree, avec_guide) VALUES (FALSE, ?, ?, ?, ?, ?, ?, ?, ?," . ($avec_guide ? "TRUE" : "FALSE") . ") RETURNING id_offre";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $description);
        $statement->bindParam(2, $resume);
        $statement->bindParam(3, $prix_mini);
        $statement->bindParam(4, $titre);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $id_type_offre);
        $statement->bindParam(7, $id_adresse);
        $statement->bindParam(8, $duree);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de créer la visite";
            return -1;
        }
    }

    static function updateVisite($est_en_ligne, $description, $resume, $prix_mini, $titre, $date, $id_pro, $id_type_offre, $id_adresse, $duree, $avec_guide)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET est_en_ligne = ?, description = ?, resume = ?, prix_mini = ?, titre = ?, date_mise_a_jour = CURRENT_TIMESTAMP, id_pro = ?, id_type_offre = ?, id_adresse = ?, duree = ?, avec_guide = ? WHERE id_offre = ? RETURNING id_offre";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $duree);
        $statement->bindParam(10, $avec_guide);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de mettre à jour la visite";
            return -1;
        }
    }

    static function deleteVisite($id)
    {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_offre = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
