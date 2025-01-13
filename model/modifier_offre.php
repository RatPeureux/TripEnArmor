<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class ModifierOffre extends BDD {
    // Nom de la table utilisée dans les requêtes
    static private $nom_table = "sae_db._offre";

    static function getOffreById($id) {
        self::initBDD();
        // Requête SQL pour sélectionner une offre par son ID
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible d'obtenir cette offre";
            return -1;
        }
    }

    static function updateOffre($id, $titre, $description, $prix_mini, $prix_max, $id_type_offre, $id_pro) {
        self::initBDD();
        // Requête SQL pour mettre à jour une offre
        $query = "UPDATE " . self::$nom_table ." SET titre = ?, description = ?, prix_mini = ?, prix_max = ?, id_type_offre = ?, id_pro = ? WHERE id_offre = ?";
        
        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $prix_mini);
        $statement->bindParam(4, $prix_max);
        $statement->bindParam(5, $id_type_offre);
        $statement->bindParam(6, $id_pro);
        $statement->bindParam(7, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return 1;
        } else {
            echo "ERREUR : Impossible de mettre à jour cette offre";
            return -1;
        }
    }
}