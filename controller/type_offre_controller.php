<?php

require_once "../model/type_offre.php";

class TypeOffreController {

    private $model;

    function __construct() {
        $this->model = 'TypeOffre';
    }


    public function getInfosTypeOffre($id){
        $typeOffre = $this->model::getProPriveById($id);

        $result = [
            "id_type_offre" => $typeOffre["id_type_offre"],
            "nom_type_offre" => $typeOffre["nom_type_offre"]
        ];

        return $result;
    }
}