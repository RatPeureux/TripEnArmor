<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/langue.php";

class LangueController {

    private $model;

    function __construct() {
        $this->model = 'Langue';
    }

    public function getInfosLangue($id){
        $langue = $this->model::getLangueById($id);

        $result = [
            "id_langue" => $langue["id_langue"],
            "nom" => $langue["nom"]
        ];

        return $result;
    }

    public function getInfosLangueByName($name){
        $langue = $this->model::getLangueByName($name);

        $result = [
            "id_langue" => $langue["id_langue"],
            "nom" => $langue["nom"]
        ];

        return $result;
   
    }
}