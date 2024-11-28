<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class Rib extends BDD {
    // Nom de la table utilisée dans les requêtes
    private $nom_table = "sae_db._rib";

    /**
     * Récupère un RIB par son ID.
     * @param int $id L'identifiant du RIB à récupérer.
     * @return array|int Retourne un tableau contenant les données du RIB ou -1 en cas d'erreur.
     */
    static function getRibById($id) {
        self::initBDD();
        // Requête SQL pour sélectionner un RIB par son ID
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_rib = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir ce rib";
            return -1;
        }
    }

    /**
     * Crée un nouveau RIB.
     * @param string $code_banque Le code banque.
     * @param string $code_guichet Le code guichet.
     * @param string $numero_compte Le numéro de compte.
     * @param string $cle La clé RIB.
     * @param int $id_compte L'identifiant du compte associé.
     * @return array|int Retourne un tableau contenant l'identifiant du nouveau RIB ou -1 en cas d'erreur.
     */
    static function createRib($code_banque, $code_guichet, $numero_compte, $cle, $id_compte) {
        self::initBDD();
        // Requête SQL pour insérer un nouveau RIB
        $query = "INSERT INTO " . self::$nom_table ." (code_banque, code_guichet, numero_compte, cle, id_compte) VALUES (?, ?, ?, ?, ?) RETURNING id_rib";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $code_banque);
        $statement->bindParam(2, $code_guichet);
        $statement->bindParam(3, $numero_compte);
        $statement->bindParam(4, $cle);
        $statement->bindParam(5, $id_compte);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_rib'];
        } else {
            echo "ERREUR : Impossible de créer le rib";
            return -1;
        }
    }

    /**
     * Met à jour un RIB existant.
     * @param string $code_banque Le nouveau code banque.
     * @param string $code_guichet Le nouveau code guichet.
     * @param string $numero_compte Le nouveau numéro de compte.
     * @param string $cle La nouvelle clé RIB.
     * @param int $id_compte Le nouvel identifiant du compte associé.
     * @return array|int Retourne un tableau contenant l'identifiant du RIB mis à jour ou -1 en cas d'erreur.
     */
    static function updateRib($id_rib, $code_banque, $code_guichet, $numero_compte, $cle, $id_compte) {
        self::initBDD();
        // Requête SQL pour mettre à jour un RIB existant
        $query = "UPDATE " . self::$nom_table ." SET code_banque = ?, code_guichet = ?, numero_compte = ?, cle = ?, id_compte = ? WHERE id_rib = ? RETURNING id_rib";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $code_banque);
        $statement->bindParam(2, $code_guichet);
        $statement->bindParam(3, $numero_compte);
        $statement->bindParam(4, $cle);
        $statement->bindParam(5, $id_compte);
        $statement->bindParam(6, $id_rib);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0]['id_rib'];
        } else {
            echo "ERREUR : Impossible de mettre à jour le rib";
            return -1;
        }
    }

    /**
     * Supprime un RIB par son ID.
     * @param int $id L'identifiant du RIB à supprimer.
     * @return array|int Retourne un tableau vide si la suppression réussit ou -1 en cas d'erreur.
     */
    static function deleteRib($id) {
        self::initBDD();
        // Requête SQL pour supprimer un RIB par son ID
        $query = "DELETE FROM " . self::$nom_table ." WHERE id_rib = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        return $statement->execute();
    }
}
