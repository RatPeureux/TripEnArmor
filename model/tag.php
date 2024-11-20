<?php

class Tag extends BDD {
    // Nom de la table utilisée dans les requêtes
    private $nom_table = "_tag";

    /**
     * Récupère un tag par son ID.
     * @param int $id L'identifiant du tag à récupérer.
     * @return array|int Retourne un tableau contenant les données du tag ou -1 en cas d'erreur.
     */
    static function getTagById($id) {
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

    /**
     * Crée un nouveau tag.
     * @param string $nom_tag Le nom du tag.
     * @return array|int Retourne un tableau contenant l'identifiant du nouveau tag ou -1 en cas d'erreur.
     */
    static function createTag($nom_tag) {
        // Requête SQL pour insérer un nouveau tag
        $query = "INSERT INTO " . self::$nom_table ." (nom_tag) VALUES (?) RETURNING id_tag";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $nom_tag);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer le tag";
            return -1;
        }
    }

    /**
     * Met à jour un tag existant.
     * @param string $nom_tag Le nouveau nom du tag.
     * @return array|int Retourne un tableau contenant l'identifiant du tag mis à jour ou -1 en cas d'erreur.
     */
    static function updateTag($id_tag, $nom_tag) {
        // Requête SQL pour mettre à jour un tag existant
        $query = "UPDATE " . self::$nom_table ." SET nom_tag = ? WHERE id_tag = ? RETURNING id_tag";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $nom_tag);
        $statement->bindParam(2, $id_tag);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le tag";
            return -1;
        }
    }

    /**
     * Supprime un tag par son ID.
     * @param int $id L'identifiant du tag à supprimer.
     * @return array|int Retourne un tableau vide si la suppression réussit ou -1 en cas d'erreur.
     */
    static function deleteTag($id) {
        // Requête SQL pour supprimer un tag par son ID
        $query = "DELETE FROM " . self::$nom_table ." WHERE id_tag = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer le tag";
            return -1;
        }
    }
}
