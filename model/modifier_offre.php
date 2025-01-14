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

    static function updateOffre($id, $titre, $description, $resume, $prix_mini, $date_creation, $date_mise_a_jour, $date_suppression, $est_en_ligne, $id_type_offre, $id_pro, $id_adresse, $option, $accessibilite) {
        self::initBDD();
        // Requête SQL pour mettre à jour une offre
        $query = "UPDATE " . self::$nom_table ." SET titre = ?, description = ?, resume = ?, prix_mini = ?, date_creation = ?, date_mise_a_jour = ?, date_suppression = ?, est_en_ligne = ?, id_type_offre = ?, id_pro = ?, id_adresse = ?, option = ?, accessibilite = ? WHERE id_offre = ?";
        
        // Vérifie si l'id_adresse existe dans la table _adresse
        $checkQuery = "SELECT COUNT(*) FROM sae_db._adresse WHERE id_adresse = ?";
        $checkStatement = self::$db->prepare($checkQuery);
        $checkStatement->bindParam(1, $id_adresse);
        $checkStatement->execute();
        if ($checkStatement->fetchColumn() == 0) {
            echo "ERREUR : L'adresse spécifiée n'existe pas";
            return -1;
        }

        // Prépare la requête SQL
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $titre);
        $statement->bindParam(2, $description);
        $statement->bindParam(3, $resume);
        $statement->bindParam(4, $prix_mini);
        $statement->bindParam(5, $date_creation);
        $statement->bindParam(6, $date_mise_a_jour);
        $statement->bindParam(7, $date_suppression);
        $statement->bindParam(8, $est_en_ligne);
        $statement->bindParam(9, $id_type_offre);
        $statement->bindParam(10, $id_pro);
        $statement->bindParam(11, $id_adresse);
        $statement->bindParam(12, $option);
        $statement->bindParam(13, $accessibilite);
        $statement->bindParam(14, $id);

        // Exécute la requête et retourne les résultats ou une erreur
        if ($statement->execute()) {
            return 1;
        } else {
            echo "ERREUR : Impossible de mettre à jour cette offre";
            return -1;
        }
    }
}