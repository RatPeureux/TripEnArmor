<?php

class TagActivite extends BDD {

    private static $nom_table = "_tag_activite";

    
    public static function getOffreById($id_offre) {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Erreur : Impossible d'obtenir l'offre avec l'ID fourni.");
        }
    }

    
    public static function getTagActiviteById($id_tag) {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_tag = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_tag, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Erreur : Impossible d'obtenir le tag d'activité avec l'ID fourni.");
        }
    }

    public static function getBothById($id_offre, $id_tag) {
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table . " WHERE id_offre = ? AND id_tag = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_tag, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Erreur : Impossible d'obtenir l'offre et le tag d'activité avec les IDs fournis.");
        }
    }

    public static function create($id_offre, $id_tag){

        self::initBDD();

        $query = "INSERT INTO " . self::$nom_table . " (id_offre, id_tag) VALUES (?, ?)";
        
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_offre);
        $stmt->bindParam(2, $id_tag);

        if ($stmt->execute()) {
            return self::$db->lastInsertId();
        } else {
            throw new Exception("Erreur : Impossible de créer une nouvelle entrée.");
            return -1;
        }

    }

    public static function update($id_offre, $id_tag, $nouvel_id_tag){

        self::initBDD();

        $query = "UPDATE " . self::$nom_table . " SET id_tag = ? WHERE id_offre = ? AND id_tag = ?";

        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $nouvel_id_tag);
        $stmt->bindParam(2, $id_offre);
        $stmt->bindParam(3, $id_tag);
        
        if ($stmt->execute()) {
            return $stmt->rowCount();
        } else {
            throw new Exception("Erreur : Impossible de mettre à jour les données.");
        }

    }

    public static function delete($id_offre, $id_tag) {
        self::initBDD();
        $query = "DELETE FROM " . self::$nom_table . " WHERE id_offre = ? AND id_tag = ?";
        $stmt = self::$db->prepare($query);

        $stmt->bindParam(1, $id_offre, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_tag, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount();
        } else {
            throw new Exception("Erreur : Impossible de supprimer les données.");
        }
    }
}
