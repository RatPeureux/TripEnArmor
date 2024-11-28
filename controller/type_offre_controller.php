<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/type_offre.php";

class TypeOffreController {

    private $model;

    function __construct() {
        $this->model = 'TypeOffre';
    }


    public function getInfosTypeOffre($id){
        $typeOffre = $this->model::getProPriveById($id);

        $result = [
            "id_type_offre" => $typeOffre["id_type_offre"],
            "nom" => $typeOffre["nom"]
        ];

        return $result;
    }
}