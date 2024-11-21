<?php

class Offre extends BDD {
    // Nom de la table utilisée pour les opérations sur les offres
    private $nom_table = "_offre";

    /**
     * Récupère une offre par son ID et son état en ligne.
     * @param int $id L'identifiant de l'offre à récupérer.
     * @param bool $enLigne Indique si l'offre doit être en ligne ou non (par défaut true).
     * @return array|int Retourne un tableau contenant les informations de l'offre ou -1 en cas d'erreur.
     */
    static function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $id_type_offre, $id_adresse) { // C
        self::initBDD();
        $query = "INSERT INTO (titre, description, resume, prix_mini, id_pro, id_type_offre, id_adresse". self::$nom_table ."VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $id_type_offre);
        $statement->bindParam(7, $id_adresse);
        
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible de créer l'offre";
            return -1;
        }
    }

    static function getOffreById($id, $enLigne = true) { // R
        // Requête SQL pour sélectionner une offre par son ID et son état en ligne
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id = ? AND est_en_ligne = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id); // évite les injections SQL
        $statement->bindValue(2, $enLigne); // valeur booléenne pour est_en_ligne

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette offre";
            return -1;
        }
    }

    /**
     * Crée une nouvelle offre dans la base de données.
     * @param string $titre Le titre de l'offre.
     * @param string $description La description complète de l'offre.
     * @param string $resume Un résumé de l'offre.
     * @param float $prix_mini Le prix minimum de l'offre.
     * @param int $id_pro L'identifiant du professionnel qui propose l'offre.
     * @param int $id_type_offre L'identifiant du type d'offre.
     * @param int $id_adresse L'identifiant de l'adresse liée à l'offre.
     * @return array|int Retourne un tableau contenant l'identifiant de la nouvelle offre ou -1 en cas d'erreur.
     */
    static function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $id_type_offre, $id_adresse) {
        // Requête SQL pour insérer une nouvelle offre
        $query = "INSERT INTO " . self::$nom_table . " (titre, description, resume, prix_mini, id_pro, id_type_offre, id_adresse) VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id_offre";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $id_pro);
        $statement->bindParam(6, $id_type_offre);
        $statement->bindParam(7, $id_adresse);
        $statement->bindParam(8, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour l'offre";
            return -1;
        }
    }
}
