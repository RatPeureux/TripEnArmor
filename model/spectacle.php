<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Spectacle extends BDD {
    static private $nom_table = "sae_db._spectacle";

    static function getSpectacleById($id, $online = true) { 
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ? AND est_en_ligne = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindValue(2, $online);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir ce spectacle";
            return -1;
        }
    }

    static function createSpectacle($est_en_ligne, $description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $capacite, $duree) {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (est_en_ligne, description, resume, prix_mini, titre, id_pro, id_type_offre, id_adresse, capacite, duree) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING id_offre";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $capacite);
        $statement->bindParam(10, $duree);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de créer le spectacle";
            return -1;
        }
    }

    static function updateSpectacle($est_en_ligne, $description, $resume, $prix_mini, $titre, $date, $id_pro, $id_type_offre, $id_adresse, $capacite, $duree) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table ." SET est_en_ligne = ?, description = ?, resume = ?, prix_mini = ?, titre = ?, date_mise_a_jour = CURRENT_TIMESTAMP, id_pro = ?, id_type_offre = ?, id_adresse = ?, capacite = ?, duree = ? WHERE id_offre = ? RETURNING id_offre";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $capacite);
        $statement->bindParam(10, $duree);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de mettre à jour le spectacle";
            return -1;
        }
    }

    static function deleteSpectacle($id) {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table ." WHERE id_offre = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        return $statement->execute();
    }
}
