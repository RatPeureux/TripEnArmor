<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/bdd.php";

class TagResto extends BDD
{
    static private $nom_table = "sae_db.vue_offre_tag";

    static function getTagRestoById($id)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag de restaurant";
            return -1;
        }
    }

    static function getTagsRestoByName($name)
    {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE nom = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $name);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ce tag de restaurant";
            return -1;
        }
    }

    static function createTagResto($nom)
    {
        self::initBDD();
        $query = "INSERT INTO " . self::$nom_table . " (nom) VALUES (?) RETURNING id_tag_resto";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le tag de restaurant";
            return -1;
        }
    }

    static function updateTagResto($nom)
    {
        self::initBDD();
        $query = "UPDATE " . self::$nom_table . " SET nom = ? RETURNING id_tag_resto";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $nom);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible de mettre à jour le tag de restaurant";
            return -1;
        }
    }
}