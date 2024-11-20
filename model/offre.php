<?php

class Offre extends BDD {
    private $nom_table = "_offre";

    static function getOffreById($id, $enLigne = true) {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE offre_id = ? AND est_en_ligne = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id); // éviter les injections SQL (Gros pbs)
        $statement->bindParam(2, $enLigne);

        if ($statement->execute()) {
            // exécution a fonctionnée
            return $statement->fetchAll(PDO::FETCH_ASSOC);
            /*
            {
                offre_id: 3
                est_en_ligne: false
                ...
            }
            */
        } else {
            // exécution n'a pas fonctionnée
            echo "ERREUR : Impossible d'obtenir cette offre";
            return -1;
        }
    }

    static function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id) {
        self::initBDD();
        $query = "INSERT INTO (titre, description, resume, prix_mini, id_pro, type_offre_id, adresse_id". self::$nom_table ."VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $type_offre_id);
        $statement->bindParam(7, $adresse_id);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer l'offre";
            return -1;
        }
    }

    // ...
}