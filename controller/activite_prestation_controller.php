<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . '/../model/bdd.php';

class ActivitePrestationController {
    private $model;

    function __construct() {
        $this->model = "ActivitePrestation";
    }

    public function getActivitesByIdPrestation($id_prestation) {
        $activites = $this->model::getActivitesByIdPrestation($id_prestation);

        $result = [
            "id_activite" => $activites["id_activite"],
        ];

        return $result;
    }

    public function getPrestationsByIdActivite($id_activite) {
        $prestations = $this->model::getPrestationsByIdActivite($id_activite);

        $result = [
            "id_prestation" => $prestations["id_prestation"],
        ];

        return $result;
    }

    public function linkActiviteAndPrestation($id_prestation, $id_activite) {
        if ($this->model::checkIfLinkExists($id_prestation, $id_activite)) {
            return $this->model::createActivitePrestation($id_prestation, $id_activite);
        } else {
            echo "The link already exists";
            return -1;
        }
    }

    public function unlinkActiviteAndPrestation($id_prestation, $id_activite) {
        if ($this->model::checkIfLinkExists($id_prestation, $id_activite)) {
            return $this->model::deleteActivitePrestation($id_prestation, $id_activite);
        } else {
            echo "The link does not exist";
            return false;
        }
    }

}