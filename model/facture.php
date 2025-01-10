<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Facture extends BDD
{
    // Nom de la table utilisée pour les opérations sur les factures
    static private $nom_table = "sae_db._facture";

    /**
     * Récupère une facture par son ID.
     * @param int $id L'identifiant de la facture à récupérer.
     * @return array|int Retourne un tableau contenant les données de la facture ou -1 en cas d'erreur.
     */
    static function getFactureById($numero)
    {
        self::initBDD();
        // Requête SQL pour sélectionner une facture par son ID
        $query = "SELECT * FROM " . self::$nom_table . " WHERE numero = ?";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, var: $numero);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cette facture";
            return -1;
        }
    }

    static function getAllFacturesByIdOffre($id_offre)
    {
        self::initBDD();
        // Requête SQL pour sélectionner une facture par son ID
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

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
    static function createFacture($id_offre, $date_echeance, $date_emission)
    {
        self::initBDD();
        // Requête SQL pour insérer une nouvelle facture
        $query = "INSERT INTO " . self::$nom_table . " (id_offre, date_echeance, date_emission) VALUES (?, ?, ?) RETURNING numero";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['numero'];
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
    static function updateFacture($numero, $date_emission, $date_echeance, $id_offre)
    {
        self::initBDD();
        // Requête SQL pour mettre à jour une facture existante
        $query = "UPDATE " . self::$nom_table . " SET date_emission = ?, id_offre = ?, date_echeance = ? WHERE numero = ? RETURNING numero";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $date_emission);
        $statement->bindParam(2, $id_offre);
        $statement->bindParam(3, $date_echeance);
        $statement->bindParam(4, $numero);


        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['numero'];
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
    static function deleteFacture($numero)
    {
        self::initBDD();
        // Requête SQL pour supprimer une facture par son ID
        $query = "DELETE FROM " . self::$nom_table . " WHERE numero = ?";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $numero);

        // Exécute la requête et retourne les résultats ou une erreur
        return $statement->execute();
    }
}
