<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Tag extends BDD {
    private $nom_table = "sae_db._tag";

    /**
     * Récupère un tag par son ID.
     * @param int $id L'identifiant du tag à récupérer.
     * @return array|int Retourne un tableau contenant les données du tag ou -1 en cas d'erreur.
     */
    static function getTagById($id) {
        self::initBDD();
        // Requête SQL pour sélectionner un tag par son ID
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_tag = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag";
            return -1;
        }
    }

    static function getTagsByName($nom) {
        self::initBDD();

        $query = "SELECT * FROM " . self::$nom_table . " WHERE nom = ?";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $nom);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag";
            return -1;
        }
    }

    static function createTag($nom) {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (nom) VALUES (?) RETURNING id_tag";

        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $nom);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_tag'];
        } else {
            echo "ERREUR : Impossible de créer le tag";
            return -1;
        }
    }
}
