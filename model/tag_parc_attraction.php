<?php

class tagParcAttraction extends BDD {

    private $nom_table = "_tag_parc_attraction";


    static function getOffreById($id_offre){

        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . "WHERE id_offre = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            echo "ERREUR : Impossible d'obtenir l'id de offre";
        }
    }

    static function getTagParcAttractionById($id_tag){

        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . "WHERE id_tag = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_tag);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            echo "ERREUR : Impossible d'obtenir l'id de l'activite";
        }
    }

    static function getBothById($id_offre, $id_tag){

        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . "WHERE id_offre = ? AND id_tag = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $id_tag);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            echo "ERREUR : Impossible d'obtenir l'id de offre et de l'activite";
        }
    }

}