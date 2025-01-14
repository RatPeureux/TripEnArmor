<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/type_offre.php";

class TypeOffreController
{

    private $model;

    function __construct()
    {
        $this->model = 'TypeOffre';
    }


    public function getInfosTypeOffre($id)
    {
        $this->model::log("Les informations du type d'offre $id ont été lues.");
        return $this->model::getTypeOffreById($id);
    }

    public function getAllTypeOffre()
    {
        $this->model::log("Les informations de tous les types d'offre ont été lues.");
        return $this->model::getAllTypeOffre();
    }
}