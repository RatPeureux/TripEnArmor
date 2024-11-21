<?php

require_once "../model/langue.php";

class LangueController {

    private $model;

    function __construct() {
        $this->model = 'Langue';
    }

    public function getInfosLangue($id){
        $langue = $this->model::getLangueById($id);

        $result = [
            "id_langue" => $langue["id_langue"],
            "nom_langue" => $langue["nom_langue"]
        ];

        return $result;
    }
}