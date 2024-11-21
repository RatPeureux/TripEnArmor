<?php 

class TypeOffre extends BDD {

    private $nom_table = "_type_offre";

    static function getTypeOffreById($id_type_offre){
        self::initBDD();
        $query = "SELECT * FROM " . self::$nom_table ." WHERE id_type_offre = ?";
        $statement = self::$db->prepare($query);
        $statement->bindParam(1, $id_type_offre);

        if ($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "ERREUR";
            return false;
        }
    }
}

?>