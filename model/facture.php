<?php

class Facture extends BDD {
    // Nom de la table utilisée pour les opérations sur les factures
    private $nom_table = "_facture";

    /**
     * Récupère une facture par son ID.
     * @param int $id L'identifiant de la facture à récupérer.
     * @return array|int Retourne un tableau contenant les données de la facture ou -1 en cas d'erreur.
     */
    static function getFactureById($id) {
        // Requête SQL pour sélectionner une facture par son ID
        $query = "SELECT * FROM " . self::$nom_table ." WHERE facture_id = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir cette facture";
            return -1;
        }
    }

    /**
     * Crée une nouvelle facture.
     * @param string $jour_en_ligne La date à laquelle la facture a été mise en ligne.
     * @return array|int Retourne un tableau contenant l'identifiant de la nouvelle facture ou -1 en cas d'erreur.
     */
    static function createFacture($jour_en_ligne) {
        // Requête SQL pour insérer une nouvelle facture
        $query = "INSERT INTO " . self::$nom_table ." (jour_en_ligne) VALUES (?) RETURNING facture_id";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $jour_en_ligne);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de créer la facture";
            return -1;
        }
    }

    /**
     * Met à jour une facture existante.
     * @param string $jour_en_ligne La nouvelle date à laquelle la facture est mise en ligne.
     * @return array|int Retourne un tableau contenant l'identifiant de la facture mise à jour ou -1 en cas d'erreur.
     */
    static function updateFacture($jour_en_ligne) {
        // Requête SQL pour mettre à jour une facture existante
        $query = "UPDATE " . self::$nom_table ." SET jour_en_ligne = ? RETURNING facture_id";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $jour_en_ligne);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour la facture";
            return -1;
        }
    }

    /**
     * Supprime une facture par son ID.
     * @param int $id L'identifiant de la facture à supprimer.
     * @return array|int Retourne un tableau vide si la suppression réussit ou -1 en cas d'erreur.
     */
    static function deleteFacture($id) {
        // Requête SQL pour supprimer une facture par son ID
        $query = "DELETE FROM " . self::$nom_table ." WHERE facture_id = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de supprimer la facture";
            return -1;
        }
    }
}
