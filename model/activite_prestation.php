<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/bdd.php";

class ActivitePrestation extends BDD {
    private $nom_table = "sae_db._activite_prestation";

    static function getActivitesByIdPrestation($id_prestation) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_prestation = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_prestation);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ces activités";
            return -1;
        }
    }

    static function getPrestationsByIdActivite($id_activite) {
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_offre = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_activite);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR : Impossible d'obtenir ces prestations";
            return -1;
        }
    }

    static function checkIfLinkExists($id_prestation, $id_activite) {
        $query = "SELECT * FROM ". self::$nom_table ." WHERE id_prestation = ? AND id_offre = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_prestation);
        $stmt->bindParam(2, $id_activite);

        if ($stmt->execute()) {
            return !empty($stmt->fetchAll(PDO::FETCH_ASSOC)[0]);
        } else {
            return false;
        }
    }

    static function createActivitePrestation($id_prestation, $id_activite) {
        $query = "INSERT INTO ". self::$nom_table ." (id_prestation, id_offre) VALUES (?, ?) RETURNING *";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_prestation);
        $stmt->bindParam(2, $id_activite);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        } else {
            echo "ERREUR : Impossible de créer ce lien";
            return -1;
        }
    }

    static function deleteActivitePrestation($id_activite, $id_prestation) {
        $query = "DELETE FROM ". self::$nom_table ." WHERE id_offre = ? AND id_prestation = ?";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $id_activite);
        $stmt->bindParam(2, $id_prestation);

        return $stmt->execute();
    }
}