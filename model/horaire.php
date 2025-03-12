<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Horaire extends BDD
{
    // Nom de la table utilisée dans les requêtes
    static private $nom_table = "sae_db._horaire";

    static function getHorairesOfOffre($id_offre)
    {
        self::initBDD();
        // Requête SQL pour sélectionner les horaires d'une offre
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_offre);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir les horaires de cette offre";
            return -1;
        }
    }

    /**
     * Récupère un horaire par son ID.
     * @param int $id L'identifiant de l'horaire à récupérer.
     * @return array|int Retourne un tableau contenant les données de l'horaire ou -1 en cas d'erreur.
     */
    static function getHoraireById($id)
    {
        self::initBDD();
        // Requête SQL pour sélectionner un horaire par son ID
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_horaire = ?";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cet horaire";
            return -1;
        }
    }

    /**
     * Crée un nouvel horaire.
     * @param string $ouverture Heure d'ouverture.
     * @param string $fermeture Heure de fermeture.
     * @param string|null $pause_debut Début de la pause (facultatif).
     * @param string|null $pause_fin Fin de la pause (facultatif).
     * @return array|int Retourne un tableau contenant l'identifiant du nouvel horaire ou -1 en cas d'erreur.
     */
    static function createHoraire($jour, $ouverture, $fermeture, $pause_debut, $pause_fin, $id_offre)
    {
        self::initBDD();
        // Requête SQL pour insérer un nouvel horaire
        $query = "INSERT INTO " . self::$nom_table . " (ouverture, fermeture, pause_debut, pause_fin, jour, id_offre) VALUES (?, ?, ?, ?, ?, ?) RETURNING id_horaire";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $ouverture);
        $statement->bindParam(2, $fermeture);
        $statement->bindParam(3, $pause_debut);
        $statement->bindParam(4, $pause_fin);
        $statement->bindParam(5, $jour);
        $statement->bindParam(6, $id_offre);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_horaire'];
        } else {
            echo "ERREUR : Impossible de créer l'horaire";
            return -1;
        }
    }

    /**
     * Met à jour un horaire existant.
     * @param string $ouverture Nouvelle heure d'ouverture.
     * @param string $fermeture Nouvelle heure de fermeture.
     * @param string|null $pause_debut Nouveau début de pause (facultatif).
     * @param string|null $pause_fin Nouvelle fin de pause (facultatif).
     * @return array|int Retourne un tableau contenant l'identifiant de l'horaire mis à jour ou -1 en cas d'erreur.
     */
    static function updateHoraire($id_horaire, $ouverture, $fermeture, $pause_debut, $pause_fin)
    {
        self::initBDD();
        // Requête SQL pour mettre à jour un horaire existant
        $query = "UPDATE " . self::$nom_table . " SET ouverture = ?, fermeture = ?, pause_debut = ?, pause_fin = ? WHERE id_horaire = ? RETURNING id_horaire";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $ouverture);
        $statement->bindParam(2, $fermeture);
        $statement->bindParam(3, $pause_debut);
        $statement->bindParam(4, $pause_fin);
        $statement->bindParam(5, $id_horaire);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]["id_horaire"];
        } else {
            echo "ERREUR : Impossible de mettre à jour l'horaire";
            return -1;
        }
    }

    /**
     * Supprime un horaire par son ID.
     * @param int $id L'identifiant de l'horaire à supprimer.
     * @return array|int Retourne un tableau vide si la suppression réussit ou -1 en cas d'erreur.
     */
    static function deleteHoraire($id)
    {
        self::initBDD();
        // Requête SQL pour supprimer un horaire par son ID
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_horaire = ?";

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        return $statement->execute();
    }
}
