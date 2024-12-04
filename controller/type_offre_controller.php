<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/type_offre.php";

class TypeOffreController
{

    private $model;

    function __construct()
    {
        $this->model = '_type_offre';
    }


    public function getInfosTypeOffre($id)
    {
        return $this->model::getTypeOffreById($id);
    }

    public function getAllTypeOffre() {
        return $this->model::getAllTypeOffre();
    }
}