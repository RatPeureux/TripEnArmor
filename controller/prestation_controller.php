<?php

require_once "../model/prestation.php";

class PrestationController {

    private $model;

    function __construct() {
        $this->model = "Prestation";
    }

    public function getPrestationById($id) {
        $this->model = $this->model::getPrestationById($id);

        return $this->model;
    }

    public function getPrestationByName($name) {
        $this->model = $this->model::getPrestationByName($name);

        return $this->model;
    }

    // TODO: à finir
}