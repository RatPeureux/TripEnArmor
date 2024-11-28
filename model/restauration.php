<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Restauration extends BDD {
    static private $nom_table = "sae_db._restauration";

    static function getRestaurationById($id, $online = true) { 
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ? AND est_en_ligne = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindValue(2, $online);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cette offre de restauration";
            return -1;
        }
    }

    static function createRestauration($est_en_ligne, $description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $gamme_prix, $id_type_repas) {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (est_en_ligne, description, resume, prix_mini, titre, id_pro, id_type_offre, id_adresse, gamme_prix, id_type_repas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING id_offre";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $gamme_prix);
        $statement->bindParam(10, $id_type_repas);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de mettre à jour l'offre de restauration";
            return -1;
        }
    }

    static function updateRestauration($est_en_ligne, $description, $resume, $prix_mini, $titre, $date, $id_pro, $id_type_offre, $id_adresse, $gamme_prix, $id_type_repas) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table ." SET est_en_ligne = ?, description = ?, resume = ?, prix_mini = ?, titre = ?, date_mise_a_jour = CURRENT_TIMESTAMP, id_pro = ?, id_type_offre = ?, id_adresse = ?, gamme_prix = ?, id_type_repas = ? WHERE id_offre = ? RETURNING id_offre";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $gamme_prix);
        $statement->bindParam(10, $id_type_repas);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de créer l'offre de restauration";
            return -1;
        }
    }

    static function deleteRestauration($id) {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table ." WHERE id_offre = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
