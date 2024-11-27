<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/bdd.php";

class ParcAttraction extends BDD {
    private $nom_table = "sae_db._parc_attraction";

    static function getParcAttractionById($id, $online = true) { 
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ? AND est_en_ligne = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);
        $statement->bindValue(2, $online);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir l'offre du parc d'attraction";
            return -1;
        }
    }

    static function createParcAttraction($est_en_ligne, $description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age_requis) {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (est_en_ligne, description, resume, prix_mini, titre, id_pro, id_type_offre, id_adresse, nb_attractions, age_requis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) RETURNING id_offre";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $nb_attractions);
        $statement->bindParam(10, $age_requis);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de créer l'offre du parc d'attraction";
            return -1;
        }
    }

    static function updateParcAttraction($est_en_ligne, $description, $resume, $prix_mini, $titre, $date, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age_requis) {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table ." SET est_en_ligne = ?, description = ?, resume = ?, prix_mini = ?, titre = ?, date_mise_a_jour = CURRENT_TIMESTAMP, id_pro = ?, id_type_offre = ?, id_adresse = ?, nb_attractions = ?, age_requis = ? WHERE id_offre = ? RETURNING id_offre";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $est_en_ligne);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $titre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id_type_offre);
        $statement->bindParam(8, $id_adresse);
        $statement->bindParam(9, $nb_attractions);
        $statement->bindParam(10, $age_requis);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_offre'];
        } else {
            echo "ERREUR : Impossible de mettre à jour l'offre du parc d'attraction";
            return -1;
        }
    }

    static function deleteParcAttraction($id) {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table ." WHERE id_offre = ?";
        
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        return $statement->execute();
    }
}
