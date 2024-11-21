<?php

class Tag extends BDD {
    private $nom_table = "_tag";

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
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag";
            return -1;
        }
    }
}
