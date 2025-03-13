<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class CatOffre extends BDD
{

    static private $nom_table = "sae_db.vue_offre_categorie";

    static function getOffreCategorie($id_cat_offre)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_cat_offre);

        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR: Impossible d'obtenir cette catégorie d'offre";
            return false;
        }
    }

}