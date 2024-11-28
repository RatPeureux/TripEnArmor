<?php

require "../model/prestation.php";

class PrestationController {

    private $model;

    function __construct() {
        $this->model = "Prestation";
    }

    public function getPrestationById($id) {
        $prestation = $this->model::getPrestationById($id);

        return $prestation;
    }

    public function getPrestationByName($name) {
        $prestation = $this->model::getPrestationByName($name);

        return $prestation;
    }

    public function createPrestation($name, $isIncluded) {
        $prestation = $this->model::createPrestation($name, $isIncluded);

        return $prestation;
    }

    public function updatePrestation($id, $name = false, $isIncluded = false) {
        if ($name === false && $isIncluded === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $prestation = $this->model::getPrestationById($id);

            $updatedPrestationId = $this->model::createPrestation(
                $id,
                $name !== false ? $name : $prestation["nom"],
                $isIncluded !== false ? $isIncluded : $prestation["is_included"]
            );
            return $updatedPrestationId;
        }
    }

    public function deletePrestation($id) {
        return $this->model::deletePrestationById($id);
    }
}