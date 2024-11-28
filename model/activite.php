<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Activite extends BDD {
    static private $nom_table = "sae_db._activite";

    static function getAllActivite() {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table;
        
        $statement = self::$db->prepare($query);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir les activitées";
            return -1;
        }
    }

    static function getActiviteById($id, $online = true) { 
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ? AND est_en_ligne = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindValue(2, $online);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cette activitée";
            return -1;
        }
    }

    static function createActivite($est_en_ligne, $description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $age_requis, $prestations) {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (est_en_ligne, description, resume, prix_mini, titre, id_pro, id_type_offre, id_adresse, duree, age_requis, prestations) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING id_offre";
        
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
        $statement->bindParam(10, $age_requis);
        $statement->bindParam(11, $prestations);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de créer l'activitée";
            return -1;
        }
    }

    static function updateActivite($est_en_ligne, $description, $resume, $prix_mini, $titre, $date, $id_pro, $id_type_offre, $id_adresse, $duree, $age_requis, $prestations) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table ." SET est_en_ligne = ?, description = ?, resume = ?, prix_mini = ?, titre = ?, date_mise_a_jour = CURRENT_TIMESTAMP, id_pro = ?, id_type_offre = ?, id_adresse = ?, duree = ?, age_requis = ?, prestation = ? WHERE id_offre = ? RETURNING id_offre";
        
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
        $statement->bindParam(10, $age_requis);
        $statement->bindParam(11, $prestations);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de mettre à jour l'activitée";
            return -1;
        }
    }

    static function deleteActivite($id) {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table ." WHERE id_offre = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
